<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = "categories";
    protected $appends = ['has_relation'];

    public function students(){
        return $this->belongsToMany(Student::class,'student_categories');
    }

    public function teachers(){
        return $this->belongsToMany(Teacher::class,'teacher_categories');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function getHasRelationAttribute()
    {
        $hasRelationWithStudent = count($this->students()->get())>0;
        $hasRelationWithTeacher = count($this->teachers()->get())>0;
        $hasRelationWithSchedule = count($this->schedules()->get())>0;
        return $hasRelationWithStudent || $hasRelationWithTeacher || $hasRelationWithSchedule;
    }
}
