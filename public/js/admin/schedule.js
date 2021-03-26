$(function () {

    let schedule_datatables = $("#schedule_datatables").DataTable({
        responsive: true,
        scrollX: true,
        pagingType: "full_numbers",
        language: {
            paginate: {
                first: "",
                last: "",
                next: "",
                previous: ""
            }
        },
        processing: true,
        serverSide: true,
        ajax: {
            type: "GET",
            url: "schedule_datatables",
            dataSrc: function (json) {
                json.data.map(function (e) {
                    // e.start_time = e.start_time.substring(0,16)
                    // e.end_time = e.end_time.substring(0,16)
                    let id = e.id
                    e.id =
                        "<div class='dropdown'>" +
                        "<button class='btn btn-sm btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                        "<i class='fa fa-cog'></i>" +
                        "</button>" +
                        "<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>" +
                        "<a class='dropdown-item btn-time' href='#' data-id='" + e.id + "'>Waktu</a>" +
                        "<a class='dropdown-item btn-edit' href='#' data-id='" + e.id + "'>Edit</a>" +
                        "<a class='" + (e.has_relation ? "d-none" : "") + " dropdown-item btn-delete' href='#' data-id='" + e.id + "'>Delete</a>" +
                        "</div>" +
                        "</div>"

                    if (e.times.length < 1) {
                        e.times = "-"
                    } else {
                        let dl = $("<dl data-id='" + id + "'></dl>")
                        e.times.forEach(time => {
                            dl.append("<dt>" +
                                "<div data-id='" + time.id + "' class='text-xs mb-1 badge badge-primary text-capitalize'>" +
                                time.day + ", " + time.start_time + " ~ " + time.end_time + " <i class='fa fa-user add-student-time'></i> <i class='fa fa-pen edit-time'></i> " + (!time.has_relation ? "<i class='delete-time fa fa-times'></i>" : "") +
                                "</div>" +
                                "</dt>")
                        })
                        e.times = dl.get(0).outerHTML
                    }

                })
                return json.data
            }
        },
        columns: [
            { data: 'id', name: 'id', width: "1%" },
            { data: 'teacher.name', name: 'teacher.name', width: "10%" },
            { data: 'category.name', name: 'category.name' },
            { data: 'max_target', name: 'max_target' },
            { data: 'times', name: 'times' }
        ],
        columnDefs: [{
            targets: 0,
            orderable: false
        }]
    })

    $("#schedule_datatables").on('show.bs.dropdown', function () {
        $(".dataTables_scrollBody").css("overflow", "inherit");
    });

    $("#schedule_datatables").on('hide.bs.dropdown', function () {
        $(".dataTables_scrollBody").css("overflow", "auto");
    })

    schedule_datatables.on('click', '.btn-delete', function () {
        if (!confirm("Anda yakin ingin menghapus jadwal ini")) {
            return
        }
        $.ajax({
            type: "POST",
            url: "schedule/" + $(this).data('id'),
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: "delete"
            },
            success: function (result) {
                if (result.success) {
                    VanillaToasts.create({
                        text: 'Data jadwal berhasil dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                } else {
                    VanillaToasts.create({
                        text: 'Data jadwal gagal dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
                schedule_datatables.ajax.reload()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: 'Data jadwal gagal dihapus!',
                    positionClass: 'topRight',
                    timeout: 3000
                });
            }
        })
    })

    $("#addScheduleForm select#category_id").on('change', function () {
        let category_id = $(this).val()
        refreshTeacherSelect(category_id);
    });

    $("#btnSaveSchedule").on('click', function () {
        let isError = false
        let formData = {
            category_id: $("#addScheduleForm select#category_id").val(),
            teacher_id: $("#addScheduleForm select#teacher_id").val(),
            max_target: $("#addScheduleForm input#max_target").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        if (formData.max_target < 1) {
            $("#addScheduleForm input#max_target").addClass("is-invalid")
            $("#max_target_error").text("Target maksimal kelas tidak boleh kosong!")
            isError = isError || true
        }

        if (!isError) {
            $.ajax({
                type: "POST",
                url: "schedule",
                data: formData,
                success: function (result) {
                    if (result.success) {
                        VanillaToasts.create({
                            text: 'Data jadwal berhasil ditambah!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                        clearAddScheduleForm()
                        $("#addScheduleModal").modal('hide')
                    } else {
                        VanillaToasts.create({
                            text: 'Data jadwal gagal ditambah!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                    }
                    schedule_datatables.ajax.reload()
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: 'Data jadwal gagal ditambah!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })

    $("#addScheduleForm input#max_target").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#max_target_error").text("")
        }
    })

    schedule_datatables.on('click', '.btn-edit', function () {
        let editModal = $("#editScheduleModal")
        $.ajax({
            type: "GET",
            url: "schedule/" + $(this).data('id') + "/edit",
            success: async function (schedule) {
                $("#edit_category_id").select2("val",schedule.category_id.toString());
                await refreshEditTeacherSelect(schedule.category_id,schedule.teacher_id)

                let teacher_select_el = $("#editScheduleForm select#edit_teacher_id")
                teacher_select_el.children('option').each(function () {
                    if ($(this).val() == schedule.teacher_id) {
                        $(this).prop('selected', true)
                    }
                })

                $("#edit_max_target").val(schedule.max_target)
                $("#btnUpdateSchedule").data("id", schedule.id)
                editModal.modal('show')
            }
        })
    })

    $("#editScheduleForm select#edit_category_id").on('change', function () {
        let category_id = $(this).val()
        refreshEditTeacherSelect(category_id);
    });

    $("#btnUpdateSchedule").on('click', function () {
        let isError = false
        let formData = {
            category_id: $("#editScheduleForm select#edit_category_id").val(),
            teacher_id: $("#editScheduleForm select#edit_teacher_id").val(),
            max_target: $("#editScheduleForm input#edit_max_target").val(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: "put"
        }
        if (formData.max_target < 1) {
            $("#editScheduleForm input#edit_max_target").addClass("is-invalid")
            $("#edit_max_target_error").text("Target kelas tidak boleh kosong!")
            isError = isError || true
        }

        if (!isError) {
            $.ajax({
                type: "POST",
                url: "schedule/" + $(this).data('id'),
                data: formData,
                success: function (result) {
                    if (result.success) {
                        VanillaToasts.create({
                            text: 'Data jadwal berhasil diedit!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                        $("#editScheduleModal").modal('hide')
                    } else {
                        VanillaToasts.create({
                            text: 'Data jadwal gagal diedit!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                    }
                    schedule_datatables.ajax.reload()
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: 'Data jadwal gagal diedit!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })

    $("#editScheduleForm input#edit_max_class").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_max_class_error").text("")
        }
    })

    function clearAddScheduleForm() {
        $("#editScheduleForm select#category_id option:selected").prop('selected', false)
        $("#addScheduleForm select#category_id>:first-child").prop('selected', true)
        let category_id = $("#addScheduleForm select#category_id").val()
        refreshTeacherSelect(category_id);
        $("#editScheduleForm input#max_target").val("")
        $("#addScheduleTimeForm input#start_time").val("")
        $("#addScheduleTimeForm input#end_time").val("")
    }

    function refreshTeacherSelect(category_id) {
        let teacher_select_el = $("#addScheduleForm select#teacher_id")
        let btn_add_schedule = $("#btnSaveSchedule")

        teacher_select_el.html('')
        teacher_select_el.attr('disabled', true)
        btn_add_schedule.attr('disabled', true)
        $.ajax({
            type: "GET",
            url: "teacher_by_category/" + category_id,
            success: function (result) {
                result.forEach(function (teacher) {
                    teacher_select_el.append("<option value='" + teacher.id + "'>" + teacher.name + "</option>")
                })
                teacher_select_el.attr('disabled', false)
                btn_add_schedule.attr('disabled', false)
            }
        })
    }

    async function refreshEditTeacherSelect(category_id,teacher_id=undefined) {
        let teacher_select_el = $("#editScheduleForm select#edit_teacher_id")
        let btn_edit_schedule = $("#btnUpdateSchedule")

        teacher_select_el.html('')
        teacher_select_el.attr('disabled', true)
        btn_edit_schedule.attr('disabled', true)
        await $.ajax({
            type: "GET",
            url: "teacher_by_category/" + category_id,
            success: function (result) {
                result.forEach(function (teacher) {
                    teacher_select_el.append("<option value='" + teacher.id + "'>" + teacher.name + "</option>")
                })
                if(teacher_id != undefined){
                    teacher_select_el.select2('val',teacher_id.toString())
                }
                teacher_select_el.attr('disabled', false)
                btn_edit_schedule.attr('disabled', false)
            }
        })
    }

    // Schedule Time

    schedule_datatables.on('click', '.btn-time', function () {
        $("#addScheduleTimeModal #btnSaveScheduleTime").data('id', $(this).data('id'))
        $("#addScheduleTimeModal input#start_time").val("")
        $("#addScheduleTimeModal input#end_time").val("")
        $("#addScheduleTimeModal").modal('show')
    })


    $("#btnSaveScheduleTime").on('click', function () {
        let isError = false
        let formData = {
            schedule_id: $(this).data('id'),
            day: $("#addScheduleTimeModal select#day").val(),
            start_time: $("#addScheduleTimeModal input#start_time").val(),
            end_time: $("#addScheduleTimeModal input#end_time").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        let start_time_ms = new Date(formData.start_time).getTime()
        let end_time_ms = new Date(formData.end_time).getTime()
        if (formData.start_time.length < 1) {
            $("#addScheduleTimeModal input#start_time").addClass("is-invalid")
            $("#start_time_error").text("Waktu mulai tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.end_time.length < 1) {
            $("#addScheduleTimeModal input#end_time").addClass("is-invalid")
            $("#end_time_error").text("Waktu selesai tidak boleh kosong!")
            isError = isError || true
        } else if (end_time_ms <= start_time_ms) {
            $("#addScheduleTimeModal input#end_time").addClass("is-invalid")
            $("#end_time_error").text("Waktu selesai harus lebih dari waktu mulai")
            isError = isError || true
        }

        if (!isError) {
            $.ajax({
                type: "POST",
                url: "schedule/time",
                data: formData,
                success: function (result) {
                    if (result.success) {
                        VanillaToasts.create({
                            text: 'Data jadwal berhasil ditambah!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                        clearAddScheduleForm()
                        $("#addScheduleTimeModal").modal('hide')
                    } else {
                        VanillaToasts.create({
                            text: result.message ? result.message : 'Data jadwal gagal ditambah!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                    }
                    schedule_datatables.ajax.reload()
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: 'Data jadwal gagal ditambah!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })

    $("#addScheduleTimeModal input#start_time").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#start_time_error").text("")
        }
    })

    $("#addScheduleTimeModal input#end_time").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#end_time_error").text("")
        }
    })

    schedule_datatables.on('click', '.delete-time', function () {
        if (!confirm("Anda yakin ingin menghapus waktu ini")) {
            return
        }
        let time_id = $(this).parent().data('id')
        let schedule_id = $(this).parent().parent().parent().data('id')
        let formData = {
            time_id: time_id,
            schedule_id: schedule_id,
            _token: $("meta[name=csrf-token]").attr('content'),
            _method: 'delete'
        }
        $.ajax({
            type: "POST",
            url: "schedule_delete_time",
            data: formData,
            success: async function (result) {
                if (result.success) {
                    VanillaToasts.create({
                        text: "Berhasil menghapus waktu!",
                        positionClass: "topRight",
                        timeout: 3000
                    })
                } else {
                    VanillaToasts.create({
                        text: result.message.length > 0 ? result.message : "Gagal menghapus waktu!",
                        positionClass: "topRight",
                        timeout: 3000
                    })
                }
                schedule_datatables.ajax.reload()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: "Gagal menghapus waktu!",
                    positionClass: "topRight",
                    timeout: 3000
                })
            }
        })

    })

    schedule_datatables.on('click', '.edit-time', function () {
        $("#editScheduleTimeModal input#edit_start_time").removeClass('is-invalid')
        $("#edit_start_time_error").text("")
        $("#editScheduleTimeModal input#edit_end_time").removeClass('is-invalid')
        $("#edit_end_time_error").text("")
        let time_id = $(this).parent().data('id')
        let schedule_id = $(this).parent().parent().parent().data('id')
        $.ajax({
            type: "GET",
            url: "get_time/" + schedule_id + "/" + time_id,
            success: function (result) {
                $("#editScheduleTimeModal").modal('show')
                let edit_day_select_el = $("#editScheduleTimeModal select#edit_day")
                edit_day_select_el.children('option').each(function () {
                    if ($(this).text() == result.time.day) {
                        $(this).prop('selected', true)
                    }
                })
                $("#editScheduleTimeModal input#edit_start_time").val(result.time.start_time)
                $("#editScheduleTimeModal input#edit_end_time").val(result.time.end_time)
                $("#editScheduleTimeModal #btnUpdateScheduleTime").data('schedule-id', schedule_id)
                $("#editScheduleTimeModal #btnUpdateScheduleTime").data('time-id', time_id)
            }
        })
    })

    $("#btnUpdateScheduleTime").on('click', function () {
        let isError = false
        let formData = {
            time_id: $(this).data('time-id'),
            schedule_id: $(this).data('schedule-id'),
            day : $("#editScheduleTimeModal select#edit_day").val(),
            start_time : $("#editScheduleTimeModal input#edit_start_time").val(),
            end_time : $("#editScheduleTimeModal input#edit_end_time").val(),
            _token: $("meta[name=csrf-token]").attr('content'),
            _method: 'put'
        }
        let start_time_ms = new Date(formData.start_time).getTime()
        let end_time_ms = new Date(formData.end_time).getTime()
        if (formData.start_time.length < 1) {
            $("#editScheduleTimeModal input#edit_start_time").addClass("is-invalid")
            $("#edit_start_time_error").text("Waktu mulai tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.end_time.length < 1) {
            $("#editScheduleTimeModal input#edit_end_time").addClass("is-invalid")
            $("#edit_end_time_error").text("Waktu selesai tidak boleh kosong!")
            isError = isError || true
        } else if (end_time_ms <= start_time_ms) {
            $("#editScheduleTimeModal input#edit_end_time").addClass("is-invalid")
            $("#edit_end_time_error").text("Waktu selesai harus lebih dari waktu mulai")
            isError = isError || true
        }
        if(isError) return

        $.ajax({
            type: "POST",
            url: "schedule_update_time",
            data: formData,
            success: function (result) {
                if (result.success) {
                    VanillaToasts.create({
                        text: 'Waktu Jadwal berhasil diedit!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                    $("#editScheduleTimeModal").modal('hide')
                } else {
                    VanillaToasts.create({
                        text: 'Waktu Jadwal gagal diedit!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
                schedule_datatables.ajax.reload()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: 'Waktu Jadwal gagal diedit!',
                    positionClass: 'topRight',
                    timeout: 3000
                });
            }
        })
    })
    
    $("#editScheduleTimeModal input#edit_start_time").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_start_time_error").text("")
        }
    })

    $("#editScheduleTimeModal input#edit_end_time").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_end_time_error").text("")
        }
    })

    schedule_datatables.on('click','.add-student-time',function(){
        let time_id = $(this).parent().data('id')
        $.ajax({
            type : "GET",
            url : "student_by_time/"+time_id,
            success: function(result){
                refreshStudentTimeList(time_id)
                $("#studentTimeModal #btnAddStudentTime").data("time-id",time_id)
                $("#studentTimeModal").modal("show")
                $("#studentTimeModal select#student_id").html('')
                result.students.forEach(function(val){
                    $("#studentTimeModal select#student_id").append("<option value='"+val.id+"'>"+val.name+"</option>")
                })
            }
        })
    })

    $("#studentTimeModal #btnAddStudentTime").on('click',function(){
        time_id = $(this).data('time-id')
        student_id = $("#studentTimeModal select#student_id").val()
        console.log(time_id)
        console.log(student_id)
        $.ajax({
            type : "POST",
            url : "student_add_schedule/"+student_id,
            data : {
                time_id : time_id,
                _token : $("meta[name=csrf-token]").attr('content')
            },
            success : function(result){
                if (result.success) {
                    VanillaToasts.create({
                        text: 'Jadwal siswa berhasil ditambah!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                    refreshStudentTimeList(time_id)
                } else {
                    VanillaToasts.create({
                        text: result.message.length > 0 ? "Siswa sudah ada!" :'Jadwal siswa gagal ditambah!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: 'Jadwal siswa gagal ditambah!',
                    positionClass: 'topRight',
                    timeout: 3000
                });
            }
        })
    })

    function refreshStudentTimeList(time_id) {
        let studentTimeList = $("#studentTimeModal #studentTimeList")
        studentTimeList.html('')
        $.ajax({
            type : "GET",
            url : "students_by_time/"+time_id,
            success: function(result){
                console.log(result)
                result.students.forEach(function(val){
                    studentTimeList.append("<li class='list-group-item' style='padding:0.5rem' data-student-id='"+val.id+"'>"+val.name+"<span style='position:absolute;right:15px'><i class='fa fa-times delete-student-time'></i></span></li>")
                })
            }
        })
    }

    $("#studentTimeModal #studentTimeList").on('click','.delete-student-time',function(){
        if(!confirm('Anda yakin ingin menghapus siswa pada jadwal ini?')){
            return
        }
        time_id = $("#studentTimeModal #btnAddStudentTime").data('time-id')
        student_id = $(this).parent().parent().data('student-id')
        $.ajax({
            type : "POST",
            url : "student_delete_schedule",
            data : {
                time_id : time_id,
                student_id : student_id,
                _token : $("meta[name=csrf-token]").attr('content'),
                _method : "delete"
            },
            success : function(result){
                if(result.success){
                    refreshStudentTimeList(time_id)
                    VanillaToasts.create({
                        text: 'Jadwal siswa berhasil dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    })
                }else{
                    VanillaToasts.create({
                        text: 'Jadwal siswa gagal dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    })
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: 'Jadwal siswa Gagal dihapus!',
                    positionClass: 'topRight',
                    timeout: 3000
                });
            }
        })
    })

})