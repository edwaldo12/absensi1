<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVTeacherReportView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement("
        // CREATE OR REPLACE VIEW v_teacher_report AS(
        //     SELECT c.teacher_id as teacher_id, t.name as teacher_name,ct.id as category_id, ct.name as category_name, COUNT(*) as open_class_count FROM classes as c
        //     LEFT JOIN teachers as t ON t.id = c.teacher_id
        //     LEFT JOIN times as tm ON tm.id = c.time_id
        //     LEFT JOIN schedules as s ON s.id = tm.schedule_id
        //     LEFT JOIN categories as ct ON ct.id = s.id
        //     GROUP BY c.teacher_id, ct.id);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::statement("DROP VIEW v_teacher_report");
    }
}
