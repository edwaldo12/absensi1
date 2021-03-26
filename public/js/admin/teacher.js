
$(function () {
    let teacher_datatables = $("#teacher_datatables").DataTable({
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
            url: "teacher_datatables",
            dataSrc: function (json) {
                json.data.map(function (e) {
                    let today = new Date();
                    let birthDate = new Date(e.date_of_birth);
                    let age = today.getFullYear() - birthDate.getFullYear();
                    let m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    e.age = age
                    e.id =
                        "<div class='dropdown'>" +
                        "<button class='btn btn-sm btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                        "<i class='fa fa-cog'></i>" +
                        "</button>" +
                        "<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>" +
                        "<a class='dropdown-item btn-schedule' href='#' data-id='" + e.id + "' data-name='"+ e.name +"'>Jadwal</a>" +                        
                        "<a class='dropdown-item btn-category' href='#' data-id='" + e.id + "' data-name='"+ e.name +"'>Kategori</a>" +
                        "<a class='dropdown-item btn-edit' href='#' data-id='" + e.id + "'>Edit</a>" +
                        "<a class='" + (e.has_relation ? "d-none" : "") + " dropdown-item btn-delete' href='#' data-id='" + e.id + "'>Delete</a>" +
                        "<div class='dropdown-divider'></div>" +
                        // "<a class='dropdown-item btn-report' href='student/report/"+e.id+"'>Laporan Absensi</a>" +
                        "<a class='dropdown-item btn-report' href='teacher/report/" + e.id + "'>Laporan Mengajar</a>" +
                        "</div>" +
                        "</div>"
                        e.name = "<span class='" + (e.gender == "Laki-Laki" ? "text-info" : "text-danger") + "'>" + e.name + "</span>"
                })
                return json.data
            }
        },
        columns: [
            { data: 'id', name: 'id', width: "1%" },
            { data: 'name', name: 'name', width: "10%" },
            { data: 'phone', name: 'phone' },
            { data: 'address', name: 'address' },
            { data: 'age', name: 'date_of_birth' },
        ],
        columnDefs: [{
            targets: 0,
            orderable: false
        }]
    })

    $("#teacher_datatables").on('show.bs.dropdown', function () {
        $(".dataTables_scrollBody").css("overflow", "inherit");
    });

    $("#teacher_datatables").on('hide.bs.dropdown', function () {
        $(".dataTables_scrollBody").css("overflow", "auto");
    })

    teacher_datatables.on('click', '.btn-delete', function () {
        if (!confirm("Anda yakin ingin menghapus guru ini")) {
            return
        }
        $.ajax({
            type: "POST",
            url: "teacher/" + $(this).data('id'),
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: "delete"
            },
            success: function (result) {
                if (result.success) {
                    VanillaToasts.create({
                        text: 'Data guru berhasil dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                } else {
                    VanillaToasts.create({
                        text: 'Data guru gagal dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
                teacher_datatables.ajax.reload()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: 'Data guru gagal dihapus!',
                    positionClass: 'topRight',
                    timeout: 3000
                });
            }
        })
    })

    $("#btnSaveTeacher").on('click', function () {
        let isError = false
        let formData = {
            name: $("#addTeacherForm input#name").val(),
            gender: $("#addTeacherForm select#gender option:selected").val(),
            place_of_birth: $("#addTeacherForm input#place_of_birth").val(),
            date_of_birth: $("#addTeacherForm input#date_of_birth").val(),
            phone: $("#addTeacherForm input#phone").val(),
            address: $("#addTeacherForm input#address").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        if (formData.name.length < 1) {
            $("#addTeacherForm input#name").addClass("is-invalid")
            $("#name_error").text("Nama tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.place_of_birth.length < 1) {
            $("#addTeacherForm input#place_of_birth").addClass("is-invalid")
            $("#place_of_birth_error").text("Tempat lahir tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.date_of_birth.length < 1) {
            $("#addTeacherForm input#date_of_birth").addClass("is-invalid")
            $("#date_of_birth_error").text("Tanggal lahir tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.phone.length < 1) {
            $("#addTeacherForm input#phone").addClass("is-invalid")
            $("#phone_error").text("Telepon tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.address.length < 1) {
            $("#addTeacherForm input#address").addClass("is-invalid")
            $("#address_error").text("Alamat tidak boleh kosong!")
            isError = isError || true
        }
        if (!isError) {
            $.ajax({
                type: "POST",
                url: "teacher",
                data: formData,
                success: function (result) {
                    if (result.success) {
                        VanillaToasts.create({
                            text: 'Data guru berhasil ditambah!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                        clearAddTeacherForm()
                        $("#addTeacherModal").modal('hide')
                    } else {
                        VanillaToasts.create({
                            text: 'Data guru gagal ditambah!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                    }
                    teacher_datatables.ajax.reload()
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: 'Data guru gagal ditambah!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })

    $("#addTeacherForm input#name").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#name_error").text("")
        }
    })

    $("#addTeacherForm input#place_of_birth").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#place_of_birth_error").text("")
        }
    })

    $("#addTeacherForm input#date_of_birth").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#date_of_birth_error").text("")
        }
    })

    $("#addTeacherForm input#phone").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#phone_error").text("")
        }
    })

    $("#addTeacherForm input#address").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#address_error").text("")
        }
    })

    teacher_datatables.on('click', '.btn-edit', function () {
        let editModal = $("#editTeacherModal")
        $.ajax({
            type: "GET",
            url: "teacher/" + $(this).data('id') + "/edit",
            success: function (result) {
                {
                    let teacher = result.teacher
                    $("#edit_name").val(teacher.name)
                    if (teacher.gender == "Laki-Laki") {
                        $("#edit_gender option[value='M']").prop("selected", true);
                    } else {
                        $("#edit_gender option[value='F']").prop("selected", true);
                    }
                    $("#edit_place_of_birth").val(teacher.place_of_birth)
                    $("#edit_date_of_birth").val(teacher.date_of_birth)
                    $("#edit_phone").val(teacher.phone)
                    $("#edit_address").val(teacher.address)
                    $("#btnUpdateTeacher").data("id", teacher.id)
                    editModal.modal('show')
                }
            }
        })
    })

    $("#btnUpdateTeacher").on('click', function () {
        let isError = false
        let formData = {
            name: $("#editTeacherForm input#edit_name").val(),
            gender: $("#editTeacherForm select#edit_gender option:selected").val(),
            place_of_birth: $("#editTeacherForm input#edit_place_of_birth").val(),
            date_of_birth: $("#editTeacherForm input#edit_date_of_birth").val(),
            phone: $("#editTeacherForm input#edit_phone").val(),
            address: $("#editTeacherForm input#edit_address").val(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: "put"
        }
        if (formData.name.length < 1) {
            $("#editTeacherForm input#edit_name").addClass("is-invalid")
            $("#edit_name_error").text("Nama tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.place_of_birth.length < 1) {
            $("#editTeacherForm input#edit_place_of_birth").addClass("is-invalid")
            $("#edit_place_of_birth_error").text("Tempat lahir tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.date_of_birth.length < 1) {
            $("#editTeacherForm input#edit_date_of_birth").addClass("is-invalid")
            $("#edit_date_of_birth_error").text("Tanggal lahir tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.phone.length < 1) {
            $("#editTeacherForm input#edit_phone").addClass("is-invalid")
            $("#edit_phone_error").text("Telepon tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.address.length < 1) {
            $("#editTeacherForm input#edit_address").addClass("is-invalid")
            $("#edit_address_error").text("Alamat tidak boleh kosong!")
            isError = isError || true
        }
        if (!isError) {
            $.ajax({
                type: "POST",
                url: "teacher/" + $(this).data('id'),
                data: formData,
                success: function (result) {
                    if (result.success) {
                        VanillaToasts.create({
                            text: 'Data guru berhasil diedit!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                        // clearAddTeacherForm()
                        $("#editTeacherModal").modal('hide')
                    } else {
                        VanillaToasts.create({
                            text: 'Data guru gagal diedit!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                    }
                    teacher_datatables.ajax.reload()
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: 'Data guru gagal diedit!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })


    $("#editTeacherForm input#edit_name").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_name_error").text("")
        }
    })

    $("#editTeacherForm input#edit_place_of_birth").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_place_of_birth_error").text("")
        }
    })

    $("#editTeacherForm input#edit_date_of_birth").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_date_of_birth_error").text("")
        }
    })

    $("#editTeacherForm input#edit_phone").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_phone_error").text("")
        }
    })

    $("#editTeacherForm input#edit_address").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_address_error").text("")
        }
    })

    function clearAddTeacherForm() {
        $("#addTeacherForm input#name").val("")
        $("#addTeacherForm input#place_of_birth").val("")
        $("#addTeacherForm input#date_of_birth").val("")
        $("#addTeacherForm input#phone").val("")
        $("#addTeacherForm input#address").val("")
        $("#addTeacherForm select#gender option[value='M']").prop('selected', true)
    }

    teacher_datatables.on('click', '.btn-category', async function () {
        $("#teacherCategoryModal #teacherCategoryModalLabel").text("Jadwal - "+$(this).data('name'))
        let teacher_id = $(this).data('id')
        $("#btnAddCategory").data('id', teacher_id)
        await refreshTeacherCategory(teacher_id)
        $("#teacherCategoryModal").modal('show')
    })

    let teacherCategoryModal = $("#teacherCategoryModal");
    teacherCategoryModal.on('click', '.delete-category', function () {
        if (!confirm("Anda yakin ingin menghapus kategori guru ini")) {
            return
        }
        let category_id = $(this).parent().data('id')
        let teacher_id = $(this).parent().parent().data('id')
        let formData = {
            teacher_id: teacher_id,
            category_id: category_id,
            _token: $("meta[name=csrf-token]").attr('content'),
            _method: 'delete'
        }
        $.ajax({
            type: "POST",
            url: "teacher_delete_category",
            data: formData,
            success: async function (result) {
                if (result.success) {
                    await refreshTeacherCategory(teacher_id);
                    VanillaToasts.create({
                        text: "Berhasil menghapus kategori!",
                        positionClass: "topRight",
                        timeout: 3000
                    })
                } else {
                    VanillaToasts.create({
                        text: result.message.length > 0 ? result.message : "Gagal menghapus kategori!",
                        positionClass: "topRight",
                        timeout: 3000
                    })
                }
                teacher_datatables.ajax.reload()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: "Gagal menghapus kategori!",
                    positionClass: "topRight",
                    timeout: 3000
                })
            }
        })
    })

    teacherCategoryModal.on('click', '#btnAddCategory', function () {
        let teacher_id = $(this).data('id')
        let formData = {
            teacher_id: teacher_id,
            category_id: $("#addCategoryForm select#category_id").val(),
            _token: $("meta[name=csrf-token]").attr('content')
        }
        $.ajax({
            type: "POST",
            url: "teacher_add_category",
            data: formData,
            success: async function (result) {
                if (result.success) {
                    await refreshTeacherCategory(teacher_id);
                    VanillaToasts.create({
                        text: "Berhasil menambahkan kategori!",
                        positionClass: "topRight",
                        timeout: 3000
                    })
                } else {
                    VanillaToasts.create({
                        text: result.message.length > 0 ? result.message : "Gagal menambahkan kategori!",
                        positionClass: "topRight",
                        timeout: 3000
                    })
                }
                teacher_datatables.ajax.reload()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: "Gagal menambahkan kategori!",
                    positionClass: "topRight",
                    timeout: 3000
                })
            }
        })
    })

    teacher_datatables.on('click', '.btn-schedule', function () {
        $("#teacherScheduleModal #teacherScheduleModalLabel").text("Jadwal - "+$(this).data('name'))
        $.ajax({
            type: "GET",
            url: "teacher_schedules/" + $(this).data('id'),
            success: function (result) {
                let teacherScheduleTable = $("#teacherScheduleModal #teacherScheduleTable>tbody")
                teacherScheduleTable.html("")
                if (result.length < 1) {
                    teacherScheduleTable.append("<tr><td colspan='2' class='text-center'>Belum ada jadwal...</td></tr>")
                } else {
                    result.forEach(schedule => {
                        let tr = $("<tr></tr>")
                        teacherScheduleTable.append(tr)
                        let dl = $("<dl style='margin:0'></dl>")
                        tr.append("<td>" + schedule.category.name + "</td>")
                        schedule.times.forEach(time => {
                            dl.append("<dt>" +
                                "<div data-id='" + time.id + "' class='text-xs mb-1 badge badge-primary'>" +
                                time.start_time + " ~ " + time.end_time +
                                "</div>" +
                                "</dt>")
                        })
                        tr.append("<td>" + dl.get(0).outerHTML + "</td>")
                    });
                }
                $("#teacherScheduleModal").modal('show')
            }
        })
    })

    async function refreshTeacherCategory(teacher_id) {
        let listCategory = $("#list-category")
        listCategory.data('id', teacher_id)
        await $.ajax({
            type: "GET",
            url: "teacher_category/" + teacher_id,
            success: function (result) {
                listCategory.text("")
                result.forEach(category => {
                    listCategory.append(
                        "<div class='badge badge-primary mr-1' data-id='" + category.id + "'>" +
                        category.name +
                        "<i class='fa fa-times delete-category' style='margin-left:2.5px'></i>" +
                        "</div>"
                    )
                });
            }
        })
    }


})
