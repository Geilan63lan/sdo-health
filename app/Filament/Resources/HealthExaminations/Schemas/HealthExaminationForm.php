<?php

namespace App\Filament\Resources\HealthExaminations\Schemas;

use App\Helpers\HealthLegend;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HealthExaminationForm
{
    public static function configure(Schema $schema): Schema
    {
        $map = HealthLegend::getMap();

        return $schema
            ->components([
                // Section 1: Metadata & Grade Context
                Section::make('Metadata & Grade Context')
                    ->schema([
                        Select::make('student_id')
                            ->relationship('student', 'first_name', fn ($query) => auth()->user()->hasRole('health_coordinator') ? $query->where('school_id', auth()->user()->school_id) : $query)
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name} ({$record->lrn})")
                            ->searchable(['first_name', 'last_name', 'lrn'])
                            ->preload()
                            ->required(),
                        Select::make('examined_by')
                            ->relationship('examinedBy', 'name')
                            ->required(),
                        Select::make('grade_level')
                            ->options([
                                'Kinder' => 'Kinder',
                                'Grade 1' => 'Grade 1',
                                'Grade 2' => 'Grade 2',
                                'Grade 3' => 'Grade 3',
                                'Grade 4' => 'Grade 4',
                                'Grade 5' => 'Grade 5',
                                'Grade 6' => 'Grade 6',
                                'Grade 7' => 'Grade 7',
                                'Grade 8' => 'Grade 8',
                                'Grade 9' => 'Grade 9',
                                'Grade 10' => 'Grade 10',
                                'Grade 11' => 'Grade 11',
                                'Grade 12' => 'Grade 12',
                            ])
                            ->required(),
                        DatePicker::make('date_of_examination')
                            ->required(),
                        TextInput::make('designation')
                            ->label('Designation/Title'),
                    ])
                    ->columns(2),

                // Section 2: Physical Growth
                Section::make('Physical Growth')
                    ->schema([
                        TextInput::make('height_cm')
                            ->label('Height')
                            ->numeric()
                            ->suffix('cm')
                            ->required(),
                        TextInput::make('weight_kg')
                            ->label('Weight')
                            ->numeric()
                            ->suffix('kg')
                            ->required(),
                        Select::make('ns_bmi_for_age')
                            ->label('BMI-for-Age')
                            ->options($map['ns_bmi'])
                            ->required(),
                        Select::make('ns_height_for_age')
                            ->label('Height-for-Age')
                            ->options($map['ns_height'])
                            ->required(),
                    ])
                    ->columns(2),

                // Section 3: Interventions
                Section::make('Interventions')
                    ->schema([
                        Checkbox::make('is_4ps_beneficiary')
                            ->label('4Ps Beneficiary'),
                        Checkbox::make('is_sbfp_beneficiary')
                            ->label('SBFP Beneficiary (Feeding Program)'),
                        Checkbox::make('deworming_july')
                            ->label('Deworming (July)'),
                        Checkbox::make('deworming_january')
                            ->label('Deworming (January)'),
                        Checkbox::make('iron_supplementation')
                            ->label('Iron Supplementation'),
                        TextInput::make('immunization_kind')
                            ->label('Immunization Kind'),
                    ])
                    ->columns(3),

                // Section 4: Vitals & Development
                Section::make('Vitals & Development')
                    ->schema([
                        TextInput::make('menarche')
                            ->label('Menarche (Female Students)'),
                        TextInput::make('temperature')
                            ->label('Temperature'),
                        TextInput::make('blood_pressure')
                            ->label('Blood Pressure'),
                        TextInput::make('pulse_rate')
                            ->label('Pulse Rate'),
                        TextInput::make('respiratory_rate')
                            ->label('Respiratory Rate'),
                    ])
                    ->columns(2),

                // Section 5: Sensory Screenings
                Section::make('Sensory Screenings')
                    ->schema([
                        Select::make('vision_l')
                            ->label('Vision (Left)')
                            ->options($map['screenings']),
                        Select::make('vision_r')
                            ->label('Vision (Right)')
                            ->options($map['screenings']),
                        Select::make('auditory_l')
                            ->label('Auditory (Left)')
                            ->options($map['screenings']),
                        Select::make('auditory_r')
                            ->label('Auditory (Right)')
                            ->options($map['screenings']),
                    ])
                    ->columns(2),

                // Section 6: Systematic Findings
                Section::make('Systematic Findings')
                    ->schema([
                        Select::make('skin_scalp')
                            ->label('Skin & Scalp')
                            ->options($map['skin_scalp']),
                        Select::make('eyes_ears_nose')
                            ->label('Eyes, Ears & Nose')
                            ->options($map['eyes_ears_nose']),
                        Select::make('mouth_neck_throat')
                            ->label('Mouth, Neck & Throat')
                            ->options($map['mouth_neck_throat']),
                        Select::make('lungs_heart')
                            ->label('Lungs & Heart')
                            ->options($map['lungs_heart']),
                        Select::make('abdomen')
                            ->label('Abdomen')
                            ->options($map['abdomen']),
                        Select::make('deformities')
                            ->label('Deformities')
                            ->options($map['deformities']),
                    ])
                    ->columns(2),

                // Section 7: Others
                Section::make('Others')
                    ->schema([
                        Textarea::make('others_specify')
                            ->label('Others/Specify')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
