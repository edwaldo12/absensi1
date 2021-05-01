<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ClassModel;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TeacherController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('teacher/index',compact('categories'));
    }

    public function create()
    {
        return view('teacher/create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $teacher = new Teacher;
            $teacher->name = $request->name;
            $teacher->gender = $request->gender;
            $teacher->place_of_birth = $request->place_of_birth;
            $teacher->date_of_birth = $request->date_of_birth;
            $teacher->phone = $request->phone;
            $teacher->address = $request->address;
            $save = $teacher->save();
            $teacherCategoryIdList = !empty($request->teacherCategoryIdList) ?array_unique($request->teacherCategoryIdList) :[];
            foreach ($teacherCategoryIdList as $category_id) {
                $teacher->categories()->attach($category_id);
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
        $teacher = Teacher::findOrFail($id);
        return response()->json(['teacher'=>$teacher]);
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->name = $request->name;
        $teacher->gender = $request->gender;
        $teacher->place_of_birth = $request->place_of_birth;
        $teacher->date_of_birth = $request->date_of_birth;
        $teacher->phone = $request->phone;
        $teacher->address = $request->address;
        return response()->json([
            'success' => $teacher->save()
        ]);
    }

    public function destroy($id)
    {
        $delete = Teacher::find($id)->delete();
        return response()->json(['success'=>$delete]);
    }

    public function teacher_datatables()
    {
        return DataTables::of(
            Teacher::orderBy("created_at","DESC")->get()
        )->make(true);
    }
    
    public function teacher_category($id)
    {
        $teacher_category = Teacher::find($id)->categories()->get();
        return response()->json($teacher_category);
    }

    public function teacher_add_category(Request $request)
    {
        $teacher = Teacher::findOrFail($request->teacher_id);
        $response['success'] = !$teacher->categories->contains($request->category_id);
        if(!$response['success']){
            $response['message'] = "Kategori sudah ada";
        }else{
            $teacher->categories()->attach($request->category_id);
        }
        return response()->json($response);
    }

    public function teacher_delete_category(Request $request)
    {
        $teacher = Teacher::findOrFail($request->teacher_id);
        $delete = $teacher->categories()->detach($request->category_id);
        return response()->json([
            'success' => $delete
        ]);
    }

    public function teacher_schedules($teacher_id)
    {
        return response()->json(
            Teacher::find($teacher_id)->schedules()->with(['category','times'])->get()
        );
    }

    public function report($teacher_id)
    {
        $teacher = Teacher::find($teacher_id);
        $reports = [];
        if(!empty($teacher)){
            $reports = $teacher->report();
        }
        return view('teacher.report',compact('teacher','reports'));
    }

    public function report_detail($teacher_id, $category_id)
    {
        $teacher = Teacher::find($teacher_id);
        $classes = ClassModel::with(['time','teacher', 'time.schedule', 'time.schedule.category', 'time.schedule.teacher'])->where(['teacher_id' => $teacher_id])->get();
        $classes = $classes->filter(function ($class) use ($category_id) {
            return $class->time->schedule->category_id == $category_id;
        });
        return view('teacher/report_detail',compact('classes','teacher'));
    }
}
