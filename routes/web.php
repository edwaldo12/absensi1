<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');


Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');
    // Schedule Route
    Route::resource('schedule', ScheduleController::class);
    Route::get('schedule_datatables', [ScheduleController::class, "schedule_datatables"]);
    Route::get('teacher_by_category/{category_id}', [ScheduleController::class, 'teacher_by_category']);
    Route::post("schedule/time",[ScheduleController::class,"schedule_add_time"]);
    Route::delete("schedule_delete_time",[ScheduleController::class,"schedule_delete_time"]);
    Route::get('get_time/{schedule_id}/{time_id}',[ScheduleController::class,"get_time"]);
    Route::put("schedule_update_time",[ScheduleController::class,"schedule_update_time"]);
    Route::get("student_by_time/{time_id}",[ScheduleController::class,"student_by_time"]);
    Route::get("students_by_time/{time_id}",[ScheduleController::class,"students_by_time"]);

    // Student Route
    Route::resource('student', StudentController::class);
    Route::get('student_datatables', [StudentController::class, "student_datatables"]);
    Route::get('student_category/{id}', [StudentController::class, "student_category"]);
    Route::post('student_add_category', [StudentController::class, "student_add_category"]);
    Route::delete('student_delete_category', [StudentController::class, "student_delete_category"]);
    Route::get('student_schedule/{student_id}', [StudentController::class, "student_schedule"]);
    Route::post('student_add_schedule/{student_id}', [StudentController::class, "student_add_schedule"]);
    Route::get('get_student_schedules/{student_id}', [StudentController::class, "get_student_schedules"]);
    Route::delete('student_delete_schedule', [StudentController::class, "student_delete_schedule"]);
    Route::get('times_by_schedule/{schedule_id}',[StudentController::class,"times_by_schedule"]);
    Route::get('student/report/{student_id}',[StudentController::class,"report"]);
    Route::get('student/report/detail/{student_id}/{category_id}',[StudentController::class,"report_detail"]);


    // Teacher Route
    Route::resource('teacher', TeacherController::class);
    Route::get('teacher_datatables', [TeacherController::class, "teacher_datatables"]);
    Route::get('teacher_category/{id}', [TeacherController::class, "teacher_category"]);
    Route::post('teacher_add_category', [TeacherController::class, "teacher_add_category"]);
    Route::delete('teacher_delete_category', [TeacherController::class, "teacher_delete_category"]);
    Route::get('teacher_schedules/{teacher_id}',[TeacherController::class,'teacher_schedules']);
    Route::get("teacher/report/{teacher_id}",[TeacherController::class,"report"]);
    Route::get("teacher/report/detail/{teacher_id}/{category_id}",[TeacherController::class,"report_detail"]);

    // Admin Route
    Route::resource('user', UserController::class);
    Route::get('user_datatables', [UserController::class, "user_datatables"]);

    // Profile Route
    Route::view("profile", "profile")->name("profile");
    Route::post("profile/update_account", [ProfileController::class, 'updateAccount'])->name("profile.updateAccount");
    Route::post("profile/change_password", [ProfileController::class, 'changePassword'])->name("profile.changePassword");

    // Category Route
    Route::resource('category', CategoryController::class);
    Route::get('category_datatables', [CategoryController::class, "category_datatables"]);

    // Class Route
    Route::resource('class', ClassController::class);
    Route::get('class_datatables', [ClassController::class, "class_datatables"]);
    Route::get('attendance/{class_id}',[ClassController::class,"attendance"]);
    Route::post('attendance/{class_id}',[ClassController::class,"save_attendance"]);
});
