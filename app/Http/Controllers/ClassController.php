<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ClassController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        $schedules = Schedule::with(['teacher', 'category'])->get();
        $times = [];
        if(count($schedules) > 0 ){
            $times = $schedules[0]->times()->get();
        }
        return view('class.index', compact('teachers', 'schedules','times'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $class = new ClassModel;
        $class->teacher_id = $request->teacher_id;
        $class->time_id = $request->time_id;
        $class->start_time = $request->start_time;
        $class->end_time = $request->end_time;
        return response()->json([
            'success' => $class->save()
        ]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        return response()->json(ClassModel::with(['teacher','time'])->find($id));
    }

    public function update(Request $request, $id)
    {
        $class = ClassModel::find($id);
        $class->teacher_id = $request->teacher_id;
        $class->time_id = $request->time_id;
        $class->start_time = $request->start_time;
        $class->end_time = $request->end_time;
        return response()->json([
            'success' => $class->save()
        ]);
    }

    public function destroy($id)
    {
        return response()->json([
            'success' => ClassModel::find($id)->delete()
        ]);
    }

    public function class_datatables()
    {
        return DataTables::of(
            ClassModel::with(['teacher', 'time.schedule.category', 'time.schedule.teacher'])->orderBy("created_at", "DESC")->get()
        )->make(true);
    }

    public function attendance($class_id)
    {
        $class = ClassModel::with('teacher', 'time.schedule.category')->find($class_id);
        $student_in_class = $class->time()->first()->students()->with(['attendances' => function($query) use ($class_id){
            $query->withTrashed()->where(['class_id'=>$class_id]);
        }])->get();
        foreach ($student_in_class as $key => $student) {
            $student_in_class[$key]->setClassId($class_id);
        }
        return view('class.attendance', compact('student_in_class', 'class_id', 'class'));
    }

    public function save_attendance(Request $request, $class_id)
    {
        $student_attendance = $request->studentAttendance;
        DB::beginTransaction();
        try {
            foreach ($student_attendance as $attendance) {
                $attendanceStudent = Attendance::withTrashed()->where(['class_id' => $class_id, 'student_id' => $attendance['student_id']])->first();
                if (!empty($attendanceStudent)) {
                    if ($attendance['is_present'] == "true") {
                        $attendanceStudent->restore();
                    }else{
                        if(empty($attendanceStudent->deleted_at)){
                            $attendanceStudent->delete();
                        }
                    }
                } else {
                    if ($attendance['is_present'] == "true") {
                        Attendance::create([
                            'student_id' => $attendance['student_id'],
                            'class_id' => $class_id
                        ]);
                    }
                }
            }
            DB::commit();
            $response['success'] = true;
            $student_in_class = ClassModel::find($class_id)->time()->first()->students()->with(['attendances' => function($query) use ($class_id){
                $query->withTrashed()->where(['class_id'=>$class_id]);
            }])->get();
            foreach ($student_in_class as $key => $student) {
                $student_in_class[$key]->setClassId($class_id);
            }
            $response['data'] = $student_in_class;
            $response['has_attendance'] = ClassModel::find($class_id)->has_attendance;
        } catch (\Exception $e) {
            DB::rollBack();
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }
}
