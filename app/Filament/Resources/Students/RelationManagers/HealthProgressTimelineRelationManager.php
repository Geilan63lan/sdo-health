<?php

namespace App\Filament\Resources\Students\RelationManagers;

use App\Filament\Resources\HealthExaminations\Schemas\HealthExaminationForm;
use App\Helpers\HealthLegend;
use App\Models\HealthExamination;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class HealthProgressTimelineRelationManager extends RelationManager
{
    protected static string $relationship = 'healthExaminations';

    private array $improvementMap = [];

    private array $declineMap = [];

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return 'Health Progress';
    }

    public function form(Schema $schema): Schema
    {
        HealthExaminationForm::configure($schema);

        $studentIdField = $schema->getComponent('student_id');
        if ($studentIdField) {
            $studentIdField->hidden()
                ->default(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->id);
        }

        $examinedByField = $schema->getComponent('examined_by');
        if ($examinedByField) {
            $examinedByField->hidden();
        }

        return $schema;
    }

    public function table(Table $table): Table
    {
        $this->buildHistoryMaps();

        $student = $this->getOwnerRecord();
        $lastExam = $student->healthExaminations()
            ->orderByDesc('date_of_examination')
            ->first();

        return $table
            ->columns([
                TextColumn::make('grade_level')
                    ->hidden(),
                TextColumn::make('date_of_examination')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('height_cm')
                    ->label('Height')
                    ->numeric()
                    ->suffix('cm'),
                TextColumn::make('weight_kg')
                    ->label('Weight')
                    ->numeric()
                    ->suffix('kg'),
                TextColumn::make('ns_bmi_for_age')
                    ->label('BMI Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => HealthLegend::label('ns_bmi', $state))
                    ->color(fn (string $state, $record): string => $this->getImprovementColor(
                        $record->id,
                        'ns_bmi_for_age',
                        $state,
                        match ($state) {
                            'a' => 'success',
                            'c' => 'danger',
                            'd' => 'warning',
                            'e' => 'danger',
                            default => 'gray',
                        },
                    )),
                TextColumn::make('ns_height_for_age')
                    ->label('Height Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => HealthLegend::label('ns_height', $state))
                    ->color(fn (string $state, $record): string => $this->getImprovementColor(
                        $record->id,
                        'ns_height_for_age',
                        $state,
                        match ($state) {
                            'f' => 'success',
                            'g' => 'warning',
                            'h' => 'danger',
                            'i' => 'info',
                            default => 'gray',
                        },
                    )),
                IconColumn::make('is_4ps_beneficiary')
                    ->label('4Ps')
                    ->boolean(),
                IconColumn::make('is_sbfp_beneficiary')
                    ->label('SBFP')
                    ->boolean(),
                IconColumn::make('deworming_july')
                    ->label('Deworm (Jul)')
                    ->boolean(),
                IconColumn::make('deworming_january')
                    ->label('Deworm (Jan)')
                    ->boolean(),
                IconColumn::make('iron_supplementation')
                    ->label('Iron')
                    ->boolean(),
                TextColumn::make('examinedBy.name')
                    ->label('Examined By'),
            ])
            ->defaultSort('date_of_examination', 'asc')
            ->groups([
                Tables\Grouping\Group::make('grade_level')
                    ->label('Grade Level')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false)
                    ->orderQueryUsing(fn (Builder $query, string $direction) => $query->orderByRaw("FIELD(grade_level, 'Kinder', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12', 'Not Recorded') $direction")
                    ),
            ])
            ->defaultGroup('grade_level')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New Health Examination')
                    ->fillForm(fn (RelationManager $livewire): array => $this->getAutoFillData($livewire->getOwnerRecord(), $lastExam))
                    ->mutateFormDataUsing(fn (array $data): array => [
                        ...$data,
                        'examined_by' => auth()->id(),
                    ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private function buildHistoryMaps(): void
    {
        $examIds = $this->getOwnerRecord()
            ->healthExaminations()
            ->orderBy('date_of_examination')
            ->pluck('id', 'id')
            ->toArray();

        $sortedExams = HealthExamination::whereIn('id', $examIds)
            ->orderBy('date_of_examination')
            ->get()
            ->keyBy('id');

        $orderedIds = array_keys($examIds);
        $previousExam = null;

        foreach ($orderedIds as $examId) {
            $currentExam = $sortedExams[$examId] ?? null;

            if ($currentExam && $previousExam) {
                foreach (['ns_height_for_age', 'ns_bmi_for_age'] as $field) {
                    $previousCode = $previousExam->$field;
                    $currentCode = $currentExam->$field;

                    if ($previousCode && $currentCode && $previousCode !== $currentCode) {
                        if ($this->isImproved($field, $previousCode, $currentCode)) {
                            $this->improvementMap[$examId][$field] = true;
                        } elseif ($this->isDeclined($field, $previousCode, $currentCode)) {
                            $this->declineMap[$examId][$field] = true;
                        }
                    }
                }
            }

            if ($currentExam) {
                $previousExam = $currentExam;
            }
        }
    }

    private function isImproved(string $field, string $from, string $to): bool
    {
        if ($field === 'ns_height_for_age') {
            $order = ['h' => 0, 'g' => 1, 'f' => 2, 'i' => 2];

            return ($order[$from] ?? 99) < ($order[$to] ?? -1);
        }

        if ($field === 'ns_bmi_for_age') {
            return $from !== 'a' && $to === 'a';
        }

        return false;
    }

    private function isDeclined(string $field, string $from, string $to): bool
    {
        if ($field === 'ns_height_for_age') {
            $order = ['h' => 0, 'g' => 1, 'f' => 2, 'i' => 2];

            return ($order[$from] ?? -1) > ($order[$to] ?? 99);
        }

        if ($field === 'ns_bmi_for_age') {
            return $from === 'a' && $to !== 'a';
        }

        return false;
    }

    private function getImprovementColor(int $examId, string $field, string $currentCode, string $defaultColor): string
    {
        if (isset($this->improvementMap[$examId][$field])) {
            return 'success';
        }

        if (isset($this->declineMap[$examId][$field])) {
            return 'danger';
        }

        return $defaultColor;
    }

    private function getAutoFillData($student, ?HealthExamination $lastExam): array
    {
        $data = [];

        if ($lastExam) {
            $data['is_4ps_beneficiary'] = $lastExam->is_4ps_beneficiary;
            $data['is_sbfp_beneficiary'] = $lastExam->is_sbfp_beneficiary;
            $data['immunization_kind'] = $lastExam->immunization_kind;
            $data['height_cm'] = $lastExam->height_cm;
            $data['weight_kg'] = $lastExam->weight_kg;
            $data['ns_bmi_for_age'] = $lastExam->ns_bmi_for_age;
            $data['ns_height_for_age'] = $lastExam->ns_height_for_age;
            $nextGrade = $this->getNextGradeLevel($lastExam->grade_level);
            if ($nextGrade) {
                $data['grade_level'] = $nextGrade;
            }
        } elseif ($student->current_grade_level) {
            $data['grade_level'] = $student->current_grade_level;
        }

        return $data;
    }

    private function getNextGradeLevel(?string $current): ?string
    {
        $sequence = [
            'Kinder' => 'Grade 1',
            'Grade 1' => 'Grade 2',
            'Grade 2' => 'Grade 3',
            'Grade 3' => 'Grade 4',
            'Grade 4' => 'Grade 5',
            'Grade 5' => 'Grade 6',
            'Grade 6' => 'Grade 7',
            'Grade 7' => 'Grade 8',
            'Grade 8' => 'Grade 9',
            'Grade 9' => 'Grade 10',
            'Grade 10' => 'Grade 11',
            'Grade 11' => 'Grade 12',
        ];

        return $sequence[$current] ?? null;
    }
}
