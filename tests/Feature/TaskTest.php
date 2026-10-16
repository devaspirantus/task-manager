<?php

namespace Tests\Feature;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_a_new_task_with_multilingual_data(): void
    {
        $task = Task::create([
            'title' => ['en' => 'Test Task', 'ar' => 'مهمة تجريبية'],
            'priority' => 'high',
            'status' => 'new',
        ]);

        $this->assertNotNull($task->id);
        $this->assertEquals('Test Task', $task->getTranslation('title', 'en'));
        $this->assertEquals('مهمة تجريبية', $task->getTranslation('title', 'ar'));
    }

    public function test_returns_title_based_on_current_locale(): void
    {
        $task = Task::create([
            'title' => ['en' => 'English Title', 'ar' => 'عنوان عربي'],
            'priority' => 'medium',
        ]);

        app()->setLocale('en');
        $this->assertEquals('English Title', $task->title);

        app()->setLocale('ar');
        $this->assertEquals('عنوان عربي', $task->title);
    }

    public function test_can_soft_delete_a_task_and_restore_it(): void
    {
        $task = Task::create([
            'title' => ['en' => 'To Delete'],
            'priority' => 'low',
            'status' => 'new',
        ]);
        
        $task->delete();
        
        $this->assertEquals(0, Task::count('id'));
        $this->assertEquals(1, Task::withTrashed()->count());
        
        $task->restore();
        $this->assertEquals(1, Task::count('id'));
    }

    public function test_identifies_overdue_tasks_correctly(): void
    {
        Task::create([
            'title' => ['en' => 'Overdue Task'],
            'due_date' => Carbon::now()->subDays(2),
            'priority' => 'high',
            'status' => 'new',
        ]);

        $overdueTasks = Task::where('due_date', '<', Carbon::now(), 'and')
            ->where('status', '!=', 'completed')
            ->get();
        
        $this->assertCount(1, $overdueTasks);
    }
}