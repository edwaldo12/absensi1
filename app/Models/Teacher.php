<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Teacher extends Model
{
    use HasFactory;
    protected $table = "teachers";
    protected $appends = ['has_relation'];
    
    public function getGenderAttribute($value)
    {
        return $value == "M" ? "Laki-Laki" : "Perempuan";
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class,'teacher_categories');
    }
    
    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }
    
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function getHasRelationAttribute()
    {
        $hasRelationWithCategory = count($this->categories()->get())>0;
        $hasRelationWithSchedule = count($this->schedules()->get())>0;
        $hasRelationWithClass = count($this->classes()->get())>0;
        return $hasRelationWithCategory || $hasRelationWithSchedule || $hasRelationWithClass;
    }

    public function report()
    {
        $reports = DB::select("SELECT c.teacher_id as teacher_id, t.name as teacher_name,ct.id as category_id, ct.name as category_name, COUNT(*) as open_class_count FROM classes as c
        LEFT JOIN teachers as t ON t.id = c.teacher_id
        LEFT JOIN times as tm ON tm.id = c.time_id
        LEFT JOIN schedules as s ON s.id = tm.schedule_id
        LEFT JOIN categories as ct ON ct.id = s.id
        WHERE c.teacher_id = '$this->id'
        GROUP BY c.teacher_id, ct.id");
        return $reports;
    }

}
