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
        $reports = DB::select("SELECT * FROM v_teacher_report WHERE teacher_id = '$this->id'");
        return $reports;
    }

}
