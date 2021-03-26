<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;
    protected $table = "students";
    protected $appends = ['is_present','has_relation'];
    public $class_id;

    public function setClassId($id)
    {
        $this->class_id = $id;
    }

    public function getGenderAttribute($value)
    {
        return $value == "M" ? "Laki-Laki" : "Perempuan";
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class,'student_categories');
    }

    public function times()
    {
        return $this->belongsToMany(Time::class,'student_times');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getIsPresentAttribute($value)
    {
        if(empty($this->class_id)){
            return null;
        }else{
            $attendance = Attendance::where(['student_id'=>$this->id,'class_id'=>$this->class_id])->first();
            return !empty($attendance);
        }
    }

    public function getHasRelationAttribute()
    {
        $hasRelationWithCategory = count($this->categories()->get())>0;
        $hasRelationWithTimes = count($this->times()->get())>0;
        $hasRelationWithAttendance = count($this->attendances()->get())>0;
        return $hasRelationWithCategory || $hasRelationWithTimes || $hasRelationWithAttendance;
    }

    public function report()
    {
        $reports = DB::select("SELECT * FROM v_student_report WHERE student_id = '$this->id'");
        return $reports;
    }
}
