<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Category;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $students = Student::all();
        return view('student/index', compact('categories', 'students'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $student = new Student;
            $student->name = $request->name;
            $student->package = $request->package;
            $student->gender = $request->gender;
            $student->place_of_birth = $request->place_of_birth;
            $student->date_of_birth = $request->date_of_birth;
            $student->phone = $request->phone;
            $student->address = $request->address;
            $save = $student->save();
            $studentCategoryIdList = !empty($request->studentCategoryIdList) ? array_unique($request->studentCategoryIdList) : [];
            foreach ($studentCategoryIdList as $category_id) {
                $student->categories()->attach($category_id);
            }
            DB::commit();
            $response["success"] = $save;
        } catch (\Exception $e) {
            DB::rollBack();
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return response()->json(['student' => $student]);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->name = $request->name;
        $student->package = $request->package;
        $student->gender = $request->gender;
        $student->place_of_birth = $request->place_of_birth;
        $student->date_of_birth = $request->date_of_birth;
        $student->phone = $request->phone;
        $student->address = $request->address;
        return response()->json([
            'success' => $student->save()
        ]);
    }

    public function destroy($id)
    {
        $delete = Student::find($id)->delete();
        return response()->json(['success' => $delete]);
    }

    public function student_datatables()
    {
        return DataTables::of(
            Student::orderBy("created_at", "DESC")->get()
        )->make(true);
    }

    public function student_category($id)
    {
        $student_category = Student::find($id)->categories()->get();
        return response()->json($student_category);
    }

    public function student_add_category(Request $request)
    {
        $student = Student::findOrFail($request->student_id);
        $response['success'] = !$student->categories->contains($request->category_id);
        if (!$response['success']) {
            $response['message'] = "Kategori sudah ada";
        } else {
            $student->categories()->attach($request->category_id);
        }
        return response()->json($response);
    }

    public function student_delete_category(Request $request)
    {
        $student = Student::findOrFail($request->student_id);
        $delete = $student->categories()->detach($request->category_id);
        return response()->json([
            'success' => $delete
        ]);
    }

    public function student_schedule($student_id)
    {
        $student = Student::find($student_id);
        $student_schedules = $student->times()->get();
        $categories = $student->categories()->with(['schedules', 'schedules.category', 'schedules.teacher', 'schedules.times'])->get();
        $times = [];
        if (count($categories) > 0) {
            for ($i = 0; $i < count($categories); $i++) {
                if (count($categories[$i]->schedules) > 0) {
                    $times = $categories[$i]->schedules[0]->times;
                    break;
                }
            }
        }
        return view('student.schedule', compact('student_schedules', 'categories', 'student_id', 'student', 'times'));
    }

    public function student_add_schedule(Request $request, $student_id)
    {
        $student = Student::find($student_id);
        $response['success'] = !$student->times->contains($request->time_id);
        if (!$response['success']) {
            $response['message'] = "Jadwal siswa sudah ada";
        } else {
            $student->times()->attach($request->time_id);
        }
        return response()->json($response);
    }

    public function get_student_schedules($student_id)
    {
        $student_schedules = Student::find($student_id)->times()->with(['schedule.teacher', 'schedule.category'])->get();
        return response()->json($student_schedules);
    }

    public function student_delete_schedule(Request $request)
    {
        $student = Student::findOrFail($request->student_id);
        $delete = $student->times()->detach($request->time_id);
        return response()->json([
            'success' => $delete
        ]);
    }

    public function times_by_schedule($schedule_id)
    {
        $times = Schedule::find($schedule_id)->times()->get();
        return response()->json($times);
    }

    public function report($student_id)
    {
        $student = Student::find($student_id);
        $reports = [];
        if (!empty($student)) {
            $reports = $student->report();
        }
        return view('student.report', compact('student', 'reports'));
    }

    public function report_detail($student_id, $category_id)
    {
        $student = Student::find($student_id);
        $details = Attendance::with(['class', 'class.time', 'class.teacher', 'class.time.schedule', 'class.time.schedule.category', 'class.time.schedule.teacher'])->where(['student_id' => $student_id])->get();
        $details = $details->filter(function ($detail) use ($category_id) {
            return $detail->class->time->schedule->category_id == $category_id;
        });
        return view('student/report_detail', compact('details', 'student'));
    }
}
