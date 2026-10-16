<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use Carbon\Carbon;


class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $tasks = [
            [
                'title' => ['en' => 'Review Laravel 13 Documentation', 'ar' => 'مراجعة وثائق لارافيل 13'],
                'description' => ['en' => 'Check new features in routing and middleware.', 'ar' => 'التحقق من الميزات الجديدة في التوجيه والـ Middleware.'],
                'due_date' => Carbon::now()->addDays(2),
                'priority' => 'high',
                'status' => 'in_progress',
            ],
            [
                'title' => ['en' => 'Design Database Schema', 'ar' => 'تصميم هيكل قاعدة البيانات'],
                'description' => ['en' => 'Finalize tables for multilingual support.', 'ar' => 'إنهاء الجداول لدعم تعدد اللغات.'],
                'due_date' => Carbon::now()->subDays(1),
                'priority' => 'high',
                'status' => 'completed',
            ],
            [
                'title' => ['en' => 'Write PHPUnit Tests', 'ar' => 'كتابة اختبارات PHPUnit'],
                'description' => ['en' => 'Cover CRUD operations and filters.', 'ar' => 'تغطية عمليات CRUD والفلاتر.'],
                'due_date' => Carbon::now()->addDays(5),
                'priority' => 'medium',
                'status' => 'new',
            ],
            [
                'title' => ['en' => 'Update README file', 'ar' => 'تحديث ملف README'],
                'description' => ['en' => 'Add deployment instructions.', 'ar' => 'إضافة تعليمات النشر.'],
                'due_date' => Carbon::now()->addDays(7),
                'priority' => 'low',
                'status' => 'new',
            ],
        ];
        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
