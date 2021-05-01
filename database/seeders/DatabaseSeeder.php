<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Student;
use App\Models\StudentCategory;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => "Admin",
            "username" => "admin",
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        User::factory(10)->create();
        Student::factory(10)->create();
        Teacher::factory(10)->create();

        $categories = ['Gitar','Piano','Drum','Harmonika'];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }

        $students = Student::all();
        foreach ($students as $student) {
            $student->categories()->attach(Category::inRandomOrder()->first()['id']);
        }

        $teachers = Teacher::all();
        foreach ($teachers as $teacher) {
            $teacher->categories()->attach(Category::inRandomOrder()->first()['id']);
        }
    }
}
