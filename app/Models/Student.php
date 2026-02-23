<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'father_name',
        'passport_number',
        'email',
        'phone',
        'dob',
        // 'current_stage',
        // 'current_status',
        'assigned_marketing_id',
        'assigned_consultant_id',
        'assigned_application_id',
        'mothers_name',
        'address',
        'ssc_result',
        'hsc_result',
        'ielts_score',
        'country_id',
        'university_id',
        'course_id',
        'course_intake_id',
        'subject',
        'created_by',
        'documents',
    ];

    protected $casts = [
        'dob' => 'date',
        'documents' => 'array',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function intake()
    {
        return $this->belongsTo(CourseIntake::class , 'course_intake_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class , 'created_by');
    }

    public function marketingAssignee()
    {
        return $this->belongsTo(User::class , 'assigned_marketing_id');
    }

    public function consultantAssignee()
    {
        return $this->belongsTo(User::class , 'assigned_consultant_id');
    }

    public function applicationAssignee()
    {
        return $this->belongsTo(User::class , 'assigned_application_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
