<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = "schedules";
    protected $appends = ['has_relation'];

    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }
    
    public function category(){
        return $this->belongsTo(Category::class);
    }

    // public function students()
    // {
    //     return $this->belongsToMany(Student::class,'student_schedules');
    // }

    // public function classes()
    // {
    //     return $this->hasMany(ClassModel::class);
    // }

    public function times()
    {
        return $this->hasMany(Time::class);
    }

    public function getHasRelationAttribute()
    {
        // $hasRelationWithStudent = count($this->students()->get())>0;
        // $hasRelationWithClass = count($this->classes()->get())>0;
        $hasRelationWithTime = count($this->times()->get())>0;
        return $hasRelationWithTime;
    }
}
