<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;
    protected $table = "classes";
    protected $appends = ['has_relation','has_attendance'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // public function schedule()
    // {
    //     return $this->belongsTo(Schedule::class);
    // }

    public function time()
    {
        return $this->belongsTo(Time::class);
    }
    
    public function attendances()
    {
        return $this->hasMany(Attendance::class,"class_id");
    }

    public function getHasRelationAttribute()
    {
        $hasRelationWithAttendance = count($this->attendances()->withTrashed()->get())>0;
        return $hasRelationWithAttendance;
    }

    public function getHasAttendanceAttribute()
    {
        $hasRelationWithAttendance = count($this->attendances()->withTrashed()->get())>0;
        return $hasRelationWithAttendance;
    }
}
