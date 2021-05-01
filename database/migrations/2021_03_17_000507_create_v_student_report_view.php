<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVStudentReportView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement("
        //     CREATE OR REPLACE VIEW v_student_report AS(
        //         SELECT `st`.`id` AS `student_id`,`st`.`name` AS `student_name`,`ct`.`id` AS `category_id`,`ct`.`name` AS `category_name`,count(0) AS `attendance_count`,
        //         `cl`.`start_time` AS `class_start_time`,`cl`.`end_time` AS `class_end_time`,`tm`.`day` AS `time_day`,`tm`.`start_time` AS `schedule_start`,
        //         `tm`.`end_time` AS `schedule_end`,`sc`.`max_target` AS `schedule_max_target`,`tc`.`name` AS `teacher_name`
        //          from ((((((`attendance`.`attendances` `at` 
        //          left join `attendance`.`students` `st` on(`st`.`id` = `at`.`student_id`)) 
        //          left join `attendance`.`classes` `cl` on(`cl`.`id` = `at`.`class_id`)) 
        //          left join `attendance`.`times` `tm` on(`tm`.`id` = `cl`.`time_id`)) 
        //          left join `attendance`.`schedules` `sc` on(`sc`.`id` = `tm`.`schedule_id`)) 
        //          left join `attendance`.`teachers` `tc` on(`tc`.`id` = `sc`.`teacher_id`)) 
        //          left join `attendance`.`categories` `ct` on(`ct`.`id` = `sc`.`category_id`)) 
        //          where `at`.`deleted_at` is null 
        //          group by `st`.`id`,`ct`.`id`
        //     );
        // ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::statement("DROP VIEW v_student_report");
    }
}
