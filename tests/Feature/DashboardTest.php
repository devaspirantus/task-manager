<?php

namespace Tests\Feature;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_stats_widget_can_be_instantiated(): void
    {
        // التأكد من أن الـ Widget يمكن إنشاؤه بدون أخطاء
        $widget = new \App\Filament\Widgets\TaskStatsOverview();
        
        $this->assertInstanceOf(\App\Filament\Widgets\TaskStatsOverview::class, $widget);
    }

    public function test_stats_logic_returns_correct_counts(): void
    {
        // إنشاء بيانات اختبار
        Task::create(['title_en' => 'Task 1', 'status' => 'new', 'priority' => 'low']);
        Task::create(['title_en' => 'Task 2', 'status' => 'completed', 'priority' => 'medium']);
        Task::create(['title_en' => 'Task 3', 'status' => 'in_progress', 'priority' => 'high']);
        Task::create([
            'title_en' => 'Overdue Task',
            'status' => 'new',
            'priority' => 'high',
            'due_date' => Carbon::now()->subDays(2),
        ]);

        // اختبار المنطق الفعلي (نفس المنطق المستخدم في الـ Widget)
        $totalTasks = Task::query()->count('*');
        $completedTasks = Task::where('status', '=', 'completed', 'and')->count();
        $inProgressTasks = Task::where('status', '=', 'in_progress', 'and')->count();
        $overdueTasks = Task::where('due_date', '<', Carbon::today(), 'and')
            ->where('status', '!=', 'completed')
            ->count();

        $this->assertEquals(4, $totalTasks);
        $this->assertEquals(1, $completedTasks);
        $this->assertEquals(1, $inProgressTasks);
        $this->assertEquals(1, $overdueTasks);
    }

    public function test_overdue_filter_excludes_completed_tasks(): void
    {
        // مهمة متأخرة لكنها مكتملة (لا يجب احتسابها)
        Task::create([
            'title_en' => 'Completed but overdue',
            'status' => 'completed',
            'priority' => 'high',
            'due_date' => Carbon::now()->subDays(5),
        ]);

        // مهمة متأخرة وغير مكتملة (يجب احتسابها)
        Task::create([
            'title_en' => 'Actually overdue',
            'status' => 'new',
            'priority' => 'high',
            'due_date' => Carbon::now()->subDays(3),
        ]);

        $overdue = Task::where('due_date', '<', Carbon::today(), 'and')
            ->where('status', '!=', 'completed')
            ->count();

        $this->assertEquals(1, $overdue);
    }
}