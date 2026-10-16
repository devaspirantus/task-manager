<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
        'due_date',
        'priority',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'priority' => 'string',
        'status' => 'string',
    ];

    // دالة مساعدة لجلب العنوان باللغة الحالية تلقائياً
    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->title_ar 
            ? $this->title_ar 
            : $this->title_en;
    }

    // دالة مساعدة لجلب الوصف باللغة الحالية تلقائياً
    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar' && $this->description_ar 
            ? $this->description_ar 
            : $this->description_en;
    }
}