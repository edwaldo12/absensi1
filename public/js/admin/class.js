
$(function () {
    let class_datatables = $("#class_datatables").DataTable({
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
            url: "class_datatables",
            dataSrc: function (json) {
                json.data.map(function (e) {
                    e.id =
                        "<div class='dropdown'>" +
                        "<button class='btn btn-sm btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                        "<i class='fa fa-cog'></i>" +
                        "</button>" +
                        "<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>" +
                        "<a class='dropdown-item btn-attendance' href='attendance/" + e.id + "'>Absensi</a>" +
                        "<a class='dropdown-item btn-edit' href='#' data-id='" + e.id + "'>Edit</a>" +
                        "<a class='" + (e.has_relation ? "d-none" : "") + " dropdown-item btn-delete' href='#' data-id='" + e.id + "'>Delete</a>" +
                        "</div>" +
                        "</div>"
                    e.class = e.time.schedule.category.name + " - " + e.time.schedule.teacher.name + "<br>" + e.time.start_time + " ~ " + e.time.end_time
                    if(e.has_attendance){
                        e.has_attendance = "<div class='badge badge-success'>Sudah</div>"
                    }else{
                        e.has_attendance = "<div class='badge badge-danger'>Belum</div>"
                    }
                })
                return json.data
            }
        },
        columns: [
            { data: 'id', name: 'id', width: "1%" },
            { data: 'has_attendance', name: 'has_attendance',},
            { data: 'teacher.name', name: 'teacher.name',},
            { data: 'class', name: 'schedule.category.name',},
            { data: 'start_time', name: 'start_time',},
            { data: 'end_time', name: 'end_time',},
        ],
        columnDefs: [{
            targets: 0,
            orderable: false
        }]
    })

    $("#class_datatables").on('show.bs.dropdown', function () {
        $(".dataTables_scrollBody").css("overflow", "inherit");
    });

    $("#class_datatables").on('hide.bs.dropdown', function () {
        $(".dataTables_scrollBody").css("overflow", "auto");
    })

    class_datatables.on('click', '.btn-delete', function () {
        if (!confirm("Anda yakin ingin menghapus kelas ini")) {
            return
        }
        $.ajax({
            type: "POST",
            url: "class/" + $(this).data('id'),
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: "delete"
            },
            success: function (result) {
                if (result.success) {
                    VanillaToasts.create({
                        text: 'Data kelas berhasil dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                } else {
                    VanillaToasts.create({
                        text: 'Data kelas gagal dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
                class_datatables.ajax.reload()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: 'Data kelas gagal dihapus!',
                    positionClass: 'topRight',
                    timeout: 3000
                });
            }
        })
    })

    $("#btnSaveClass").on('click', function () {
        let isError = false
        let formData = {
            teacher_id: $("#addClassForm select#teacher_id option:selected").val(),
            time_id: $("#addClassForm select#time_id option:selected").val(),
            start_time: $("#addClassForm input#start_time").val(),
            end_time: $("#addClassForm input#end_time").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        let start_time_ms = new Date(formData.start_time).getTime()
        let end_time_ms = new Date(formData.end_time).getTime()
        if (formData.start_time.length < 1) {
            $("#addClassForm input#start_time").addClass("is-invalid")
            $("#start_time_error").text("Waktu mulai tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.end_time.length < 1) {
            $("#addClassForm input#end_time").addClass("is-invalid")
            $("#end_time_error").text("Waktu selesai tidak boleh kosong!")
            isError = isError || true
        } else if (end_time_ms <= start_time_ms) {
            $("#addClassForm input#end_time").addClass("is-invalid")
            $("#end_time_error").text("Waktu selesai harus lebih dari waktu mulai")
            isError = isError || true
        }

        if (!isError) {
            $.ajax({
                type: "POST",
                url: "class",
                data: formData,
                success: function (result) {
                    if (result.success) {
                        VanillaToasts.create({
                            text: 'Data kelas berhasil ditambah!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                        clearAddClassForm()
                        $("#addClassModal").modal('hide')
                    } else {
                        VanillaToasts.create({
                            text: 'Data kelas gagal ditambah!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                    }
                    class_datatables.ajax.reload()
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: 'Data kelas gagal ditambah!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })

    $("#addClassForm input#start_time").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#start_time_error").text("")
        }
    })

    $("#addClassForm input#end_time").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#end_time_error").text("")
        }
    })

    class_datatables.on('click', '.btn-edit', function () {
        let editModal = $("#editClassModal")
        $.ajax({
            type: "GET",
            url: "class/" + $(this).data('id') + "/edit",
            success: function (result) {
                $("#editClassForm select#edit_teacher_id").select2('val', result.teacher_id.toString())
                $("#editClassForm select#edit_schedule_id").select2('val', result.time.schedule_id.toString())

                $("#edit_start_time").val(new Date(result.start_time).toISOString().split('GMT')[0].substring(0, 16))
                $("#edit_end_time").val(new Date(result.end_time).toISOString().split('GMT')[0].substring(0, 16))
                $("#btnUpdateClass").data("id", result.id)
                editModal.modal('show')
            }
        })
    })

    $("#btnUpdateClass").on('click', function () {
        let isError = false
        let formData = {
            teacher_id: $("#editClassForm select#edit_teacher_id option:selected").val(),
            // schedule_id: $("#editClassForm select#edit_schedule_id option:selected").val(),
            time_id: $("#editClassForm select#edit_time_id option:selected").val(),
            start_time: $("#editClassForm input#edit_start_time").val(),
            end_time: $("#editClassForm input#edit_end_time").val(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: "put"
        }
        let start_time_ms = new Date(formData.start_time).getTime()
        let end_time_ms = new Date(formData.end_time).getTime()
        if (formData.start_time.length < 1) {
            $("#editClassForm input#edit_start_time").addClass("is-invalid")
            $("#edit_start_time_error").text("Waktu mulai tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.end_time.length < 1) {
            $("#editClassForm input#edit_end_time").addClass("is-invalid")
            $("#edit_end_time_error").text("Waktu selesai tidak boleh kosong!")
            isError = isError || true
        } else if (end_time_ms <= start_time_ms) {
            $("#editClassForm input#edit_end_time").addClass("is-invalid")
            $("#edit_end_time_error").text("Waktu selesai harus lebih dari waktu mulai")
            isError = isError || true
        }

        if (!isError) {
            $.ajax({
                type: "POST",
                url: "class/" + $(this).data('id'),
                data: formData,
                success: function (result) {
                    if (result.success) {
                        VanillaToasts.create({
                            text: 'Data kelas berhasil diedit!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                        $("#editClassModal").modal('hide')
                    } else {
                        VanillaToasts.create({
                            text: 'Data kelas gagal diedit!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                    }
                    class_datatables.ajax.reload()
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: 'Data kelas gagal diedit!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })

    $("#editClassForm input#edit_start_time").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_start_time_error").text("")
        }
    })

    $("#editClassForm input#edit_end_time").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_end_time_error").text("")
        }
    })
    
    $("#editClassForm select#edit_schedule_id").on('change',function(){
        refreshTimeSelect($(this).val())
    })
    $("#addClassForm select#schedule_id").on('change',function(){
        refreshTimeSelect($(this).val())
    })
    
    async function refreshTimeSelect(schedule_id) {
        let edit_time_select_el = $("#editClassForm select#edit_time_id")
        edit_time_select_el.html("")
        let time_select_el = $("#addClassForm select#time_id")
        time_select_el.html("")
        let btn_update_schedule = $("#btnUpdateClass")
        let btn_save_schedule = $("#btnSaveClass")

        edit_time_select_el.html('')
        edit_time_select_el.attr('disabled', true)
        time_select_el.html('')
        time_select_el.attr('disabled', true)
        btn_save_schedule.attr('disabled', true)
        btn_update_schedule.attr('disabled', true)
        await $.ajax({
            type: "GET",
            url: "../times_by_schedule/" + schedule_id,
            success: function (result) {
                console.log(result)
                result.forEach(function (time) {
                    edit_time_select_el.append("<option value='" + time.id + "'>"+time.day+", " + time.start_time + " ~ "+ time.end_time +"</option>")
                    time_select_el.append("<option value='" + time.id + "'>"+time.day+", " + time.start_time + " ~ "+ time.end_time +"</option>")
                })
                edit_time_select_el.attr('disabled', false)
                time_select_el.attr('disabled', false)
                btn_save_schedule.attr('disabled', false)
                btn_update_schedule.attr('disabled', false)
            }
        })
    }

    function clearAddClassForm() {
        $("#addClassForm select#teacher_id option:selected").prop('selected', false)
        $("#addClassForm select#teacher_id>:first-child").prop('selected', true)
        $("#addClassForm select#schedule_id option:selected").prop('selected', false)
        $("#addClassForm select#schedule_id>:first-child").prop('selected', true)
        $("#addClassForm input#start_time").val("")
        $("#addClassForm input#end_time").val("")
    }
})
