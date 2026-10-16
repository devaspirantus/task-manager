<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TaskStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1; // يظهر في الأعلى

    protected function getStats(): array
    {
        $totalTasks = Task::count('*');
        $completedTasks = Task::where('status', '=', 'completed', 'and')->count();
        $inProgressTasks = Task::where('status', '=', 'in_progress', 'and')->count();
        $overdueTasks = Task::where('due_date', '<', Carbon::today(), 'and')
            ->where('status', '!=', 'completed')
            ->count();

        return [
            Stat::make('إجمالي المهام', $totalTasks)
                ->description('جميع المهام المسجلة')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary'),

            Stat::make('المكتملة', $completedTasks)
                ->description('المهام التي تم إنجازها')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('قيد التنفيذ', $inProgressTasks)
                ->description('المهام التي يتم العمل عليها')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),

            Stat::make('المتأخرة', $overdueTasks)
                ->description('مهام تجاوزت تاريخ الاستحقاق')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($overdueTasks > 0 ? 'danger' : 'gray'),
        ];
    }
}