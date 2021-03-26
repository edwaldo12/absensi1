$(function () {
    let student_datatables = $("#student_datatables").DataTable({
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
            url: "student_datatables",
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
                        "<a class='dropdown-item' href='student_schedule/" + e.id + "'>Jadwal</a>" +
                        "<a class='dropdown-item btn-category' href='#' data-id='" + e.id + "' data-name='"+ e.name +"'>Kategori</a>" +
                        "<a class='dropdown-item btn-edit' href='#' data-id='" + e.id + "'>Edit</a>" +
                        "<a class='" + (e.has_relation ? "d-none" : "") + " dropdown-item btn-delete' href='#' data-id='" + e.id + "'>Delete</a>" +
                        "<div class='dropdown-divider'></div>" +
                        "<a class='dropdown-item btn-report' href='student/report/"+e.id+"'>Laporan Absensi</a>" +
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

    $("#student_datatables").on('show.bs.dropdown', function () {
        $(".dataTables_scrollBody").css("overflow", "inherit");
    });

    $("#student_datatables").on('hide.bs.dropdown', function () {
        $(".dataTables_scrollBody").css("overflow", "auto");
    })


    student_datatables.on('click', '.btn-delete', function () {
        if (!confirm("Anda yakin ingin menghapus siswa ini")) {
            return
        }
        $.ajax({
            type: "POST",
            url: "student/" + $(this).data('id'),
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: "delete"
            },
            success: function (result) {
                if (result.success) {
                    VanillaToasts.create({
                        text: 'Data siswa berhasil dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                } else {
                    VanillaToasts.create({
                        text: 'Data siswa gagal dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
                student_datatables.ajax.reload()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: 'Data siswa gagal dihapus!',
                    positionClass: 'topRight',
                    timeout: 3000
                });
            }
        })
    })

    $("#btnSaveStudent").on('click', function () {
        let isError = false
        let formData = {
            name: $("#addStudentForm input#name").val(),
            gender: $("#addStudentForm select#gender option:selected").val(),
            place_of_birth: $("#addStudentForm input#place_of_birth").val(),
            date_of_birth: $("#addStudentForm input#date_of_birth").val(),
            phone: $("#addStudentForm input#phone").val(),
            address: $("#addStudentForm input#address").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        if (formData.name.length < 1) {
            $("#addStudentForm input#name").addClass("is-invalid")
            $("#name_error").text("Nama tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.place_of_birth.length < 1) {
            $("#addStudentForm input#place_of_birth").addClass("is-invalid")
            $("#place_of_birth_error").text("Tempat lahir tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.date_of_birth.length < 1) {
            $("#addStudentForm input#date_of_birth").addClass("is-invalid")
            $("#date_of_birth_error").text("Tanggal lahir tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.phone.length < 1) {
            $("#addStudentForm input#phone").addClass("is-invalid")
            $("#phone_error").text("Telepon tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.address.length < 1) {
            $("#addStudentForm input#address").addClass("is-invalid")
            $("#address_error").text("Alamat tidak boleh kosong!")
            isError = isError || true
        }
        if (!isError) {
            $.ajax({
                type: "POST",
                url: "student",
                data: formData,
                success: function (result) {
                    if (result.success) {
                        VanillaToasts.create({
                            text: 'Data siswa berhasil ditambah!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                        clearAddStudentForm()
                        $("#addStudentModal").modal('hide')
                    } else {
                        VanillaToasts.create({
                            text: 'Data siswa gagal ditambah!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                    }
                    student_datatables.ajax.reload()
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: 'Data siswa gagal ditambah!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })

    $("#addStudentForm input#name").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#name_error").text("")
        }
    })

    $("#addStudentForm input#place_of_birth").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#place_of_birth_error").text("")
        }
    })

    $("#addStudentForm input#date_of_birth").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#date_of_birth_error").text("")
        }
    })

    $("#addStudentForm input#phone").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#phone_error").text("")
        }
    })

    $("#addStudentForm input#address").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#address_error").text("")
        }
    })

    student_datatables.on('click', '.btn-edit', function () {
        let editModal = $("#editStudentModal")
        $.ajax({
            type: "GET",
            url: "student/" + $(this).data('id') + "/edit",
            success: function (result) {
                {
                    let student = result.student
                    $("#edit_name").val(student.name)
                    if (student.gender == "Laki-Laki") {
                        $("#edit_gender option[value='M']").prop("selected", true);
                    } else {
                        $("#edit_gender option[value='F']").prop("selected", true);
                    }
                    $("#edit_place_of_birth").val(student.place_of_birth)
                    $("#edit_date_of_birth").val(student.date_of_birth)
                    $("#edit_phone").val(student.phone)
                    $("#edit_address").val(student.address)
                    $("#btnUpdateStudent").data("id", student.id)
                    editModal.modal('show')
                }
            }
        })
    })

    $("#btnUpdateStudent").on('click', function () {
        let isError = false
        let formData = {
            name: $("#editStudentForm input#edit_name").val(),
            gender: $("#editStudentForm select#edit_gender option:selected").val(),
            place_of_birth: $("#editStudentForm input#edit_place_of_birth").val(),
            date_of_birth: $("#editStudentForm input#edit_date_of_birth").val(),
            phone: $("#editStudentForm input#edit_phone").val(),
            address: $("#editStudentForm input#edit_address").val(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: "put"
        }
        if (formData.name.length < 1) {
            $("#editStudentForm input#edit_name").addClass("is-invalid")
            $("#edit_name_error").text("Nama tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.place_of_birth.length < 1) {
            $("#editStudentForm input#edit_place_of_birth").addClass("is-invalid")
            $("#edit_place_of_birth_error").text("Tempat lahir tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.date_of_birth.length < 1) {
            $("#editStudentForm input#edit_date_of_birth").addClass("is-invalid")
            $("#edit_date_of_birth_error").text("Tanggal lahir tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.phone.length < 1) {
            $("#editStudentForm input#edit_phone").addClass("is-invalid")
            $("#edit_phone_error").text("Telepon tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.address.length < 1) {
            $("#editStudentForm input#edit_address").addClass("is-invalid")
            $("#edit_address_error").text("Alamat tidak boleh kosong!")
            isError = isError || true
        }
        if (!isError) {
            $.ajax({
                type: "POST",
                url: "student/" + $(this).data('id'),
                data: formData,
                success: function (result) {
                    if (result.success) {
                        VanillaToasts.create({
                            text: 'Data siswa berhasil diedit!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                        // clearAddStudentForm()
                        $("#editStudentModal").modal('hide')
                    } else {
                        VanillaToasts.create({
                            text: 'Data siswa gagal diedit!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                    }
                    student_datatables.ajax.reload()
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: 'Data siswa gagal diedit!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })


    $("#editStudentForm input#edit_name").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_name_error").text("")
        }
    })

    $("#editStudentForm input#edit_place_of_birth").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_place_of_birth_error").text("")
        }
    })

    $("#editStudentForm input#edit_date_of_birth").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_date_of_birth_error").text("")
        }
    })

    $("#editStudentForm input#edit_phone").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_phone_error").text("")
        }
    })

    $("#editStudentForm input#edit_address").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#edit_address_error").text("")
        }
    })

    function clearAddStudentForm() {
        $("#addStudentForm input#name").val("")
        $("#addStudentForm input#place_of_birth").val("")
        $("#addStudentForm input#date_of_birth").val("")
        $("#addStudentForm input#phone").val("")
        $("#addStudentForm input#address").val("")
        $("#addStudentForm select#gender option[value='M']").prop('selected', true)
    }

    student_datatables.on('click', '.btn-category', async function () {
        $('#studentCategoryModal #studentCategoryModalLabel').text("Kategori Siswa - "+$(this).data('name'))

        let student_id = $(this).data('id')
        $("#btnAddCategory").data('id', student_id)
        await refreshStudentCategory(student_id)
        $("#studentCategoryModal").modal('show')
    })

    let studentCategoryModal = $("#studentCategoryModal");
    studentCategoryModal.on('click', '.delete-category', function () {
        if (!confirm("Anda yakin ingin menghapus kategori siswa ini")) {
            return
        }
        let category_id = $(this).parent().data('id')
        let student_id = $(this).parent().parent().data('id')
        let formData = {
            student_id: student_id,
            category_id: category_id,
            _token: $("meta[name=csrf-token]").attr('content'),
            _method: 'delete'
        }
        $.ajax({
            type: "POST",
            url: "student_delete_category",
            data: formData,
            success: async function (result) {
                if (result.success) {
                    await refreshStudentCategory(student_id);
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
                student_datatables.ajax.reload()
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

    studentCategoryModal.on('click', '#btnAddCategory', function () {
        let student_id = $(this).data('id')
        let formData = {
            student_id: student_id,
            category_id: $("#addCategoryForm select#category_id").val(),
            _token: $("meta[name=csrf-token]").attr('content')
        }
        $.ajax({
            type: "POST",
            url: "student_add_category",
            data: formData,
            success: async function (result) {
                if (result.success) {
                    await refreshStudentCategory(student_id);
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
                student_datatables.ajax.reload()
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

    async function refreshStudentCategory(student_id) {
        let listCategory = $("#list-category")
        listCategory.data('id', student_id)
        await $.ajax({
            type: "GET",
            url: "student_category/" + student_id,
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

    $("#btnStudentReport").on('click',function(){
        let student_id = $("#studentReportForm select#student_id").val()
        window.location.href = "student/report/"+student_id
    })
})
