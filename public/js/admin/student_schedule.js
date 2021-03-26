$(function () {
    $('#btnAddStudentSchedule').on('click', function () {
        let student_id = $(this).data('id')
        let formData = {
            schedule_id : $('#addStudentScheduleForm select#schedule_id').val(),
            time_id : $('#addStudentScheduleForm select#time_id').val(),
            _token : $("meta[name=csrf-token]").attr('content')
        }
        $.ajax({
            type : 'POST',
            url: '../student_add_schedule/' + student_id,
            data: formData,
            success: async function (result) {
                if(result.success){
                    await refreshStudentScheduleTable(student_id);
                    VanillaToasts.create({
                        text : "Berhasil menambahkan jadwal siswa!",
                        positionClass : "topRight",
                        timeout : 3000
                    })
                }else{
                    VanillaToasts.create({
                        text : result.message.length > 0 ? result.message : "Gagal menambahkan jadwal siswa!",
                        positionClass : "topRight",
                        timeout : 3000
                    })
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: 'Gagal menambahkan jadwal siswa!',
                    positionClass: 'topRight',
                    timeout: 3000
                });
            }
        })
    })

    $('#student_schedules_table').on('click', '.btn-delete', function () {
        if (!confirm("Anda yakin ingin jadwal siswa ini")) {
            return
        }
        let formData = {
            student_id: $("#btnAddStudentSchedule").data('id'),
            time_id: $(this).data('id'),
            _token: $("meta[name=csrf-token]").attr('content'),
            _method : 'delete'
        }
        $.ajax({
            type: 'POST' ,
            url: '../student_delete_schedule',
            data: formData,
            success: async function (result) {
                if (result.success) {
                    await refreshStudentScheduleTable(formData.student_id)
                    VanillaToasts.create({
                        text: 'Jadwal siswa berhasil dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                } else {
                    VanillaToasts.create({
                        text: 'Jadwal siswa gagal dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: 'Jadwal siswa gagal dihapus!',
                    positionClass: 'topRight',
                    timeout: 3000
                });
            }
        })
    })

    $("#addStudentScheduleForm select#schedule_id").on('change',function(){
        refreshTimeSelect($(this).val())
    })
    
    async function refreshTimeSelect(schedule_id) {
        let time_select_el = $("#addStudentScheduleForm select#time_id")
        let btn_save_schedule = $("#btnAddStudentSchedule")

        time_select_el.html('')
        time_select_el.attr('disabled', true)
        btn_save_schedule.attr('disabled', true)
        await $.ajax({
            type: "GET",
            url: "../times_by_schedule/" + schedule_id,
            success: function (result) {
                result.forEach(function (time) {
                    time_select_el.append("<option value='" + time.id + "'>"+time.day+", " + time.start_time + " ~ "+ time.end_time +"</option>")
                })
                time_select_el.attr('disabled', false)
                btn_save_schedule.attr('disabled', false)
            }
        })
    }

    async function refreshStudentScheduleTable(student_id) {
        await $.ajax({
            type :'GET',
            url  : '../get_student_schedules/'+student_id,
            success: function (result) {
                let student_schedules_table = $("#student_schedules_table>tbody")
                student_schedules_table.html("")
                result.forEach(e => {
                    student_schedules_table.append(
                        "<tr>" +
                            "<td>" +
                                "<div class='dropdown'>" +
                                "<button class='btn btn-sm btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                                "<i class='fa fa-cog'></i>" +
                                "</button>" +
                                "<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>" +
                                "<a class='dropdown-item btn-delete' href='#' data-id='" + e.id + "'>Delete</a>" +
                                "</div>" +
                                "</div>"+
                            "</td>" +
                            "<td>"+e.schedule.category.name+"</td>"+
                            "<td>"+e.schedule.teacher.name+"</td>"+
                            "<td>"+e.day+", "+e.start_time+" ~ "+e.end_time+"</td>"+
                        "</tr>"
                    )
                })
            }
        })  
    }
})