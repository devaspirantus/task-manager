<?php

namespace App\Filament\Resources\Tasks\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. العنوان مع البحث في اللغتين والترتيب الذكي
      TextColumn::make('title_en') // نستخدم العنوان الإنجليزي كأساس
    ->label('العنوان')
    ->formatStateUsing(function ($state, $record) {
        // المنطق: إذا كانت اللغة عربية ويوجد عنوان عربي، اعرضه. وإلا اعرض الإنجليزي
        return app()->getLocale() === 'ar' && $record->title_ar
            ? $record->title_ar
            : $record->title_en;
    })
    ->searchable(['title_en', 'title_ar']) // تفعيل البحث في اللغتين معاً
    ->sortable()
    ->limit(40),

                // 2. الحالة مع شارات ملونة (Badges)
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'gray',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => 'جديدة',
                        'in_progress' => 'قيد التنفيذ',
                        'completed' => 'مكتملة',
                        default => $state,
                    })
                    ->sortable()
                    ->searchable(),

                // 3. الأولوية مع شارات ملونة
                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'success',
                        'medium' => 'warning',
                        'high' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'low' => 'منخفض',
                        'medium' => 'متوسط',
                        'high' => 'مرتفع',
                        default => $state,
                    })
                    ->sortable()
                    ->searchable(),

                // 4. تاريخ الاستحقاق
                TextColumn::make('due_date')
                    ->date('Y-m-d')
                    ->sortable()
                    ->label('تاريخ الاستحقاق'),

                // الأعمدة الافتراضية المخفية (كما ولدها Filament)
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // فلتر المحذوفات المدمج
                TrashedFilter::make(),

                // فلاترنا المخصصة
                SelectFilter::make('status')
                    ->options([
                        'new' => 'جديدة',
                        'in_progress' => 'قيد التنفيذ',
                        'completed' => 'مكتملة',
                    ])
                    ->label('تصفية حسب الحالة'),

                SelectFilter::make('priority')
                    ->options([
                        'low' => 'منخفض',
                        'medium' => 'متوسط',
                        'high' => 'مرتفع',
                    ])
                    ->label('تصفية حسب الأولوية'),

                Filter::make('overdue')
                    ->label('المهام المتأخرة فقط')
                    ->query(fn ($query) => $query->where('due_date', '<', Carbon::today())->where('status', '!=', 'completed')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(), // أضفنا زر الحذف الفردي هنا
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('due_date', 'asc'); // الترتيب الافتراضي الذكي
    }
}