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

    public function getPackageAttribute($value)
    {
        return $value == 1 ? "Regular" : "Private";
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
        $reports = DB::select("SELECT `st`.`id` AS `student_id`,`st`.`name` AS `student_name`,`ct`.`id` AS `category_id`,`ct`.`name` AS `category_name`,count(0) AS `attendance_count`,
        `cl`.`start_time` AS `class_start_time`,`cl`.`end_time` AS `class_end_time`,`tm`.`day` AS `time_day`,`tm`.`start_time` AS `schedule_start`,
        `tm`.`end_time` AS `schedule_end`,`sc`.`max_target` AS `schedule_max_target`,`tc`.`name` AS `teacher_name`
         from ((((((`attendance`.`attendances` `at` 
         left join `attendance`.`students` `st` on(`st`.`id` = `at`.`student_id`)) 
         left join `attendance`.`classes` `cl` on(`cl`.`id` = `at`.`class_id`)) 
         left join `attendance`.`times` `tm` on(`tm`.`id` = `cl`.`time_id`)) 
         left join `attendance`.`schedules` `sc` on(`sc`.`id` = `tm`.`schedule_id`)) 
         left join `attendance`.`teachers` `tc` on(`tc`.`id` = `sc`.`teacher_id`)) 
         left join `attendance`.`categories` `ct` on(`ct`.`id` = `sc`.`category_id`)) 
         where `at`.`deleted_at` is null 
         AND student_id = '$this->id'
         group by `st`.`id`,`ct`.`id` ");
        return $reports;
    }
}
