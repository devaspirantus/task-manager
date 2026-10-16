<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    TextInput::make('title_en')
                        ->required()
                        ->maxLength(255)
                        ->label('العنوان (English)'),
                    
                    TextInput::make('title_ar')
                        ->maxLength(255)
                        ->label('العنوان (العربية)'),
                ]),

                Grid::make(2)->schema([
                    Textarea::make('description_en')
                        ->label('الوصف (English)')
                        ->columnSpanFull(),
                    
                    Textarea::make('description_ar')
                        ->label('الوصف (العربية)')
                        ->columnSpanFull(),
                ]),

                Grid::make(3)->schema([
                    DatePicker::make('due_date')
                        ->label('تاريخ الاستحقاق')
                        ->native(false)
                        ->displayFormat('Y-m-d'),

                    Select::make('priority')
                        ->options([
                            'low' => 'منخفض / Low',
                            'medium' => 'متوسط / Medium',
                            'high' => 'مرتفع / High',
                        ])
                        ->default('medium')
                        ->required()
                        ->label('الأولوية'),

                    Select::make('status')
                        ->options([
                            'new' => 'جديدة / New',
                            'in_progress' => 'قيد التنفيذ / In Progress',
                            'completed' => 'مكتملة / Completed',
                        ])
                        ->default('new')
                        ->required()
                        ->label('الحالة'),
                ]),
            ]);
    }
}