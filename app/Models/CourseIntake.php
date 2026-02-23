<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseIntake extends Model
{
    protected $fillable = [
        'course_id',
        'intake_name',
        'application_start_date',
        'application_deadline',
        'class_start_date',
        'status',
    ];

    protected $casts = [
        'application_start_date' => 'date',
        'application_deadline' => 'date',
        'class_start_date' => 'date',
        'status' => 'boolean',
    ];

    // Relationship
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

