<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            [
                'title_en' => 'Review Laravel 13 Documentation',
                'title_ar' => 'مراجعة وثائق لارافيل 13',
                'description_en' => 'Check new features in routing and middleware.',
                'description_ar' => 'التحقق من الميزات الجديدة في التوجيه والـ Middleware.',
                'due_date' => Carbon::now()->addDays(2),
                'priority' => 'high',
                'status' => 'in_progress',
            ],
            [
                'title_en' => 'Design Database Schema',
                'title_ar' => 'تصميم هيكل قاعدة البيانات',
                'description_en' => 'Finalize tables for multilingual support.',
                'description_ar' => 'إنهاء الجداول لدعم تعدد اللغات.',
                'due_date' => Carbon::now()->subDays(1),
                'priority' => 'high',
                'status' => 'completed',
            ],
            [
                'title_en' => 'Write PHPUnit Tests',
                'title_ar' => 'كتابة اختبارات PHPUnit',
                'description_en' => 'Cover CRUD operations and filters.',
                'description_ar' => 'تغطية عمليات CRUD والفلاتر.',
                'due_date' => Carbon::now()->addDays(5),
                'priority' => 'medium',
                'status' => 'new',
            ],
            [
                'title_en' => 'Update README file',
                'title_ar' => 'تحديث ملف README',
                'description_en' => 'Add deployment instructions.',
                'description_ar' => 'إضافة تعليمات النشر.',
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