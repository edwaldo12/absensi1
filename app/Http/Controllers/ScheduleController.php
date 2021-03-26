<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Schedule;
use App\Models\Time;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ScheduleController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $teachers = $categories[0]->teachers()->get();
        return view('schedule.index', compact('categories', 'teachers'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // TO DO : VALIDATE EXISTING SCHEDULE
        $schedule = new Schedule;
        $schedule->category_id = $request->category_id;
        $schedule->teacher_id = $request->teacher_id;
        $schedule->max_target = $request->max_target;
        return response()->json([
            'success' => $schedule->save()
        ]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $schedule = Schedule::with(['teacher', 'category'])->find($id);
        return response()->json($schedule);
    }

    public function update(Request $request, $id)
    {
        // TO DO : VALIDATE EXISTING SCHEDULE
        $schedule = Schedule::findOrFail($id);
        $schedule->teacher_id = $request->teacher_id;
        $schedule->category_id = $request->category_id;
        $schedule->max_target = $request->max_target;
        return response()->json([
            'success' => $schedule->save()
        ]);
    }

    public function destroy($id)
    {
        $delete = Schedule::find($id)->delete();
        return response()->json(['success' => $delete]);
    }

    public function schedule_datatables()
    {
        return DataTables::of(
            Schedule::with(['teacher', 'category', 'times'])->orderBy("created_at", "DESC")->get()
        )->make(true);
    }

    public function teacher_by_category($category_id)
    {
        $teachers = Category::find($category_id)->teachers()->get();
        return response()->json($teachers);
    }

    public function schedule_add_time(Request $request)
    {
        $schedule = Schedule::find($request->schedule_id);
        $value = ['day' => $request->day, 'start_time' => $request->start_time, 'end_time' => $request->end_time];
        $is_exist = count($schedule->times()->where($value)->get()) > 0;
        if (!$is_exist) {
            $schedule->times()->create($value);
        } else {
            $response['message'] = "Waktu sudah ada!";
        }
        $response['success'] = !$is_exist;
        return response()->json($response);
    }

    public function schedule_delete_time(Request $request)
    {
        $schedule = Schedule::find($request->schedule_id);
        return response()->json([
            'success' => $schedule->times()->find($request->time_id)->delete()
        ]);
    }

    public function get_time($schedule_id, $time_id)
    {
        $schedule = Schedule::find($schedule_id);
        return response()->json([
            'time' => $schedule->times()->find($time_id)
        ]);
    }
    public function schedule_update_time(Request $request)
    {
        $schedule = Schedule::find($request->schedule_id);
        $time = $schedule->times()->find($request->time_id);
        $time->day = $request->day;
        $time->start_time = $request->start_time;
        $time->end_time = $request->end_time;
        return response()->json([
            'success' => $time->save()
        ]);
    }

    public function student_by_time($time_id)
    {
        $students = Time::find($time_id)->schedule()->first()->category()->first()->students()->get();
        return response()->json(['students'=>$students]);
    }

    public function students_by_time($time_id)
    {
        $students = Time::find($time_id)->students()->get();
        return response()->json(['students'=>$students]);
    }
}
