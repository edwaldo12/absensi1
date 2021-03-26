<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Time;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Student::count();
        $student_this_month = Student::whereMonth('created_at', "=", Carbon::now()->month)->count();
        $student_last_month = Student::whereMonth('created_at', "=", Carbon::now()->subMonth()->month)->count();
        $student_growth = 0;
        if($student_last_month > 0){
            $student_growth = number_format(($student_this_month / $student_last_month)*100,2);
        }
        $teacher = Teacher::count();
        $teacher_this_month = Teacher::whereMonth('created_at', "=", Carbon::now()->month)->count();
        $teacher_last_month = Teacher::whereMonth('created_at', "=", Carbon::now()->subMonth()->month)->count();
        $teacher_growth = 0;
        if($teacher_last_month > 0){
            $teacher_growth = number_format(($teacher_this_month / $teacher_last_month)*100,2);
        }
        $schedule = Schedule::count();
        $schedule_this_month = Schedule::whereMonth('created_at', "=", Carbon::now()->month)->count();
        $schedule_last_month = Schedule::whereMonth('created_at', "=", Carbon::now()->subMonth()->month)->count();
        $schedule_growth = 0;
        if($schedule_last_month > 0){
            $schedule_growth = number_format(($schedule_this_month / $schedule_last_month)*100,2);
        }
        $class = ClassModel::count();
        $class_this_month = ClassModel::whereMonth('created_at', "=", Carbon::now()->month)->count();
        $class_last_month = ClassModel::whereMonth('created_at', "=", Carbon::now()->subMonth()->month)->count();
        $class_growth = 0;
        if($class_last_month > 0){
            $class_growth = number_format(($class_this_month / $class_last_month)*100,2);
        }
        $hari =[
            "Senin" => 1,
            "Selasa" => 2,
            "Rabu" => 3,
            "Kamis" => 4,
            "Jumat" => 5,
            "Sabtu" => 6,
            "Minggu" => 7
        ];
        $day_name_now = $hari[Carbon::now()->translatedFormat('l')];
        $today_schedules = Time::with(['schedule','schedule.teacher','schedule.category'])->where(['day'=>$day_name_now])->get();
        $schedules = Schedule::with(['category','teacher'])->get();
        $times = Time::all();
        $teachers = Teacher::all();
        return view('dashboard',compact('student','student_growth','teacher','teacher_growth','schedule','schedule_growth','class','class_growth','today_schedules','schedules','times','teachers'));
    }
}
