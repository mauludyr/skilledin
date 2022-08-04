<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "user_id",
        "grade_id",
        "job_position_id",
        "employment_type_id",
        "salaray_id",
        "salary"
    ];

    /** Relation to User */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /** Relation To Grade */
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }

    /** Relation To Job Position */
    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class, 'job_position_id', 'id');
    }

    /** Relation To Job Employment Type */
    public function employmentType()
    {
        return $this->belongsTo(EmploymentType::class, 'employment_type_id', 'id');
    }

    /** Relation to Nationality */
    public function salaryCode()
    {
        return $this->belongsTo(Location::class, 'salaray_id', 'id');
    }
}


