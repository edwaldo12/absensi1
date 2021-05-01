
$(function () {
    let user_datatables = $("#user_datatables").DataTable({
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
            url: "user_datatables",
            dataSrc: function (json) {
                json.data.map(function (e) {
                    e.id =
                        "<div class='dropdown'>" +
                        "<button class='btn btn-sm btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                        "<i class='fa fa-cog'></i>" +
                        "</button>" +
                        "<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>" +
                        "<a class='dropdown-item btn-edit' href='#' data-id='" + e.id + "'>Edit</a>" +
                        "<a class='dropdown-item btn-delete' href='#' data-id='" + e.id + "'>Delete</a>" +
                        "</div>" +
                        "</div>"
                })
                return json.data
            }
        },
        columns: [
            { data: 'id', name: 'id', width: "1%" },
            { data: 'name', name: 'name', width: "10%" },
            { data: 'username', name: 'username' },
        ],
        columnDefs: [{
            targets: 0,
            orderable: false
        }]
    })

    $("#user_datatables").on('show.bs.dropdown', function () {
        $(".dataTables_scrollBody").css("overflow", "inherit");
    });

    $("#user_datatables").on('hide.bs.dropdown', function () {
        $(".dataTables_scrollBody").css("overflow", "auto");
    })

    user_datatables.on('click', '.btn-delete', function () {
        if (!confirm("Anda yakin ingin menghapus admin ini")) {
            return
        }
        $.ajax({
            type: "POST",
            url: "user/" + $(this).data('id'),
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: "delete"
            },
            success: function (result) {
                if (result.success) {
                    VanillaToasts.create({
                        text: 'Data admin berhasil dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                } else {
                    VanillaToasts.create({
                        text: 'Data admin gagal dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
                user_datatables.ajax.reload()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: 'Data admin gagal dihapus!',
                    positionClass: 'topRight',
                    timeout: 3000
                });
            }
        })
    })

    $("#btnSaveAdmin").on('click', function () {
        let isError = false
        let formData = {
            name: $("#addAdminForm input#name").val(),
            username: $("#addAdminForm input#username").val(),
            password: $("#addAdminForm input#password").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        if (formData.name.length < 1) {
            $("#addAdminForm input#name").addClass("is-invalid")
            $("#name_error").text("Nama tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.username.length < 1) {
            $("#addAdminForm input#username").addClass("is-invalid")
            $("#username_error").text("Username tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.password.length < 1) {
            $("#addAdminForm input#password").addClass("is-invalid")
            $("#password_error").text("Password tidak boleh kosong!")
            isError = isError || true
        }
        if (!isError) {
            $.ajax({
                type: "POST",
                url: "user",
                data: formData,
                success: function (result) {
                    if (result.success) {
                        VanillaToasts.create({
                            text: 'Data admin berhasil ditambah!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                        clearAddAdminForm()
                        $("#addAdminModal").modal('hide')
                    } else {
                        VanillaToasts.create({
                            text: 'Data admin gagal ditambah!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                    }
                    user_datatables.ajax.reload()
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: 'Data admin gagal ditambah!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })

    $("#addAdminForm input#name").on('blur', function () {
        if ($(this).val().length > 1) {
            $(this).removeClass("is-invalid")
            $("#name_error").text("")
        }
    })

    $("#addAdminForm input#username").on('blur', function () {
        if ($(this).val().length > 1) {
            $(this).removeClass("is-invalid")
            $("#username_error").text("")
        }
    })

    $("#addAdminForm input#password").on('blur', function () {
        if ($(this).val().length > 1) {
            $(this).removeClass("is-invalid")
            $("#password_error").text("")
        }
    })

    user_datatables.on('click', '.btn-edit', function () {
        let editModal = $("#editAdminModal")
        $.ajax({
            type: "GET",
            url: "user/" + $(this).data('id') + "/edit",
            success: function (result) {
                {
                    let user = result.user
                    $("#edit_name").val(user.name)
                    $("#edit_username").val(user.username)
                    $("#edit_password").val(user.password)
                    $("#btnUpdateAdmin").data("id", user.id)
                    editModal.modal('show')
                }
            }
        })
    })

    $("#btnUpdateAdmin").on('click', function () {
        let isError = false
        let formData = {
            name: $("#editAdminForm input#edit_name").val(),
            username: $("#editAdminForm input#edit_username").val(),
            password: $("#editAdminForm input#edit_password").val(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: "put"
        }
        if (formData.name.length < 1) {
            $("#editAdminForm input#edit_name").addClass("is-invalid")
            $("#edit_name_error").text("Nama tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.username.length < 1) {
            $("#editAdminForm input#edit_username").addClass("is-invalid")
            $("#edit_username_error").text("Username tidak boleh kosong!")
            isError = isError || true
        }
        if (!isError) {
            $.ajax({
                type: "POST",
                url: "user/" + $(this).data('id'),
                data: formData,
                success: function (result) {
                    if (result.success) {
                        VanillaToasts.create({
                            text: 'Data admin berhasil diedit!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                        // clearAddAdminForm()
                        $("#editAdminModal").modal('hide')
                    } else {
                        VanillaToasts.create({
                            text: 'Data admin gagal diedit!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                    }
                    user_datatables.ajax.reload()
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: 'Data admin gagal diedit!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })

    $("#editAdminForm input#edit_name").on('blur', function () {
        if ($(this).val().length > 1) {
            $(this).removeClass("is-invalid")
            $("#edit_name_error").text("")
        }
    })

    $("#editAdminForm input#edit_username").on('blur', function () {
        if ($(this).val().length > 1) {
            $(this).removeClass("is-invalid")
            $("#edit_username_error").text("")
        }
    })

    function clearAddAdminForm() {
        $("#addAdminForm input#name").val("")
        $("#addAdminForm input#username").val("")
        $("#addAdminForm input#password").val("")
    }
})
