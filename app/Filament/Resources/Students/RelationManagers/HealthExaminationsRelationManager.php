<?php

namespace App\Filament\Resources\Students\RelationManagers;

use App\Filament\Resources\HealthExaminations\Schemas\HealthExaminationForm;
use App\Helpers\HealthLegend;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class HealthExaminationsRelationManager extends RelationManager
{
    protected static string $relationship = 'healthExaminations';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return 'Health Examinations';
    }

    public function form(Schema $schema): Schema
    {
        HealthExaminationForm::configure($schema);

        // Hide student_id as it's already linked
        $studentIdField = $schema->getComponent('student_id');
        if ($studentIdField) {
            $studentIdField->hidden()
                ->default(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->id);
        }

        // Hide examined_by - value will be set via mutateFormDataUsing
        $examinedByField = $schema->getComponent('examined_by');
        if ($examinedByField) {
            $examinedByField->hidden();
        }

        return $schema;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('grade_level')
                    ->label('Grade Level')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_examination')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('height_cm')
                    ->label('Height (cm)')
                    ->numeric(),
                Tables\Columns\TextColumn::make('weight_kg')
                    ->label('Weight (kg)')
                    ->numeric(),
                Tables\Columns\TextColumn::make('ns_bmi_for_age')
                    ->label('BMI-for-Age')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => HealthLegend::label('ns_bmi', $state))
                    ->color(fn (string $state): string => match ($state) {
                        'a' => 'danger',
                        'c' => 'warning',
                        'd' => 'success',
                        'e' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('ns_height_for_age')
                    ->label('Height-for-Age')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => HealthLegend::label('ns_height', $state))
                    ->color(fn (string $state): string => match ($state) {
                        'f' => 'danger',
                        'g' => 'warning',
                        'h' => 'success',
                        'i' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('examinedBy.name')
                    ->label('Examined By'),
            ])
            ->defaultSort('date_of_examination', 'desc')
            ->filters([])
            ->recordActions([])
            ->headerActions([])
            ->bulkActions([]);
    }

    private function getAutoFillData($student): array
    {
        $data = [];

        $lastExam = $student->healthExaminations()
            ->orderByDesc('date_of_examination')
            ->first();

        if ($lastExam) {
            $data['is_4ps_beneficiary'] = $lastExam->is_4ps_beneficiary;
            $data['is_sbfp_beneficiary'] = $lastExam->is_sbfp_beneficiary;
            $data['immunization_kind'] = $lastExam->immunization_kind;
            $data['height_cm'] = $lastExam->height_cm;
            $data['weight_kg'] = $lastExam->weight_kg;
            $data['ns_bmi_for_age'] = $lastExam->ns_bmi_for_age;
            $data['ns_height_for_age'] = $lastExam->ns_height_for_age;
            $nextGrade = GradeLevel::next($lastExam->grade_level);
            if ($nextGrade) {
                $data['grade_level'] = $nextGrade;
            }
        } elseif ($student->current_grade_level) {
            $data['grade_level'] = $student->current_grade_level;
        }

        return $data;
    }
}
