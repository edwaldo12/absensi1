
$(function () {
    let category_datatables = $("#category_datatables").DataTable({
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
            url: "category_datatables",
            dataSrc: function (json) {
                json.data.map(function (e) {
                    e.id =
                        "<div class='dropdown'>" +
                        "<button class='btn btn-sm btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>" +
                        "<i class='fa fa-cog'></i>" +
                        "</button>" +
                        "<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>" +
                        "<a class='dropdown-item btn-edit' href='#' data-id='" + e.id + "'>Edit</a>" +
                        "<a class='" + (e.has_relation ? "d-none" : "") + " dropdown-item btn-delete' href='#' data-id='" + e.id + "'>Delete</a>" +
                        "</div>" +
                        "</div>"
                })
                return json.data
            }
        },
        columns: [
            { data: 'id', name: 'id', width: "1%" },
            { data: 'name', name: 'name', width: "10%" },
        ],
        columnDefs: [{
            targets: 0,
            orderable: false
        }]
    })

    $("#category_datatables").on('show.bs.dropdown', function () {
        $(".dataTables_scrollBody").css("overflow", "inherit");
    });

    $("#category_datatables").on('hide.bs.dropdown', function () {
        $(".dataTables_scrollBody").css("overflow", "auto");
    })

    category_datatables.on('click', '.btn-delete', function () {
        if (!confirm("Anda yakin ingin menghapus kategori ini")) {
            return
        }
        $.ajax({
            type: "POST",
            url: "category/" + $(this).data('id'),
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: "delete"
            },
            success: function (result) {
                if (result.success) {
                    VanillaToasts.create({
                        text: 'Data kategori berhasil dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                } else {
                    VanillaToasts.create({
                        text: result.message ? result.message : 'Data kategori gagal dihapus!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
                category_datatables.ajax.reload()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: 'Data kategori gagal dihapus!',
                    positionClass: 'topRight',
                    timeout: 3000
                });
            }
        })
    })

    $("#btnSaveCategory").on('click', function () {
        let isError = false
        let formData = {
            name: $("#addCategoryForm input#name").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        }
        if (formData.name.length < 1) {
            $("#addCategoryForm input#name").addClass("is-invalid")
            $("#name_error").text("Nama tidak boleh kosong!")
            isError = isError || true
        }

        if (!isError) {
            $.ajax({
                type: "POST",
                url: "category",
                data: formData,
                success: function (result) {
                    if (result.success) {
                        VanillaToasts.create({
                            text: 'Data kategori berhasil ditambah!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                        clearAddCategoryForm()
                        $("#addCategoryModal").modal('hide')
                    } else {
                        VanillaToasts.create({
                            text: result.message ? result.message : 'Data kategori gagal ditambah!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                    }
                    category_datatables.ajax.reload()
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: 'Data kategori gagal ditambah!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })

    $("#addCategoryForm input#name").on('blur', function () {
        if ($(this).val().length > 1) {
            $(this).removeClass("is-invalid")
            $("#name_error").text("")
        }
    })

    category_datatables.on('click', '.btn-edit', function () {
        let editModal = $("#editCategoryModal")
        $.ajax({
            type: "GET",
            url: "category/" + $(this).data('id') + "/edit",
            success: function (result) {
                let category = result.category
                $("#edit_name").val(category.name)
                $('#btnUpdateCategory').data('id', category.id)
                editModal.modal('show')
            }
        })
    })

    $("#btnUpdateCategory").on('click', function () {
        let isError = false
        let formData = {
            name: $("#editCategoryModal input#edit_name").val(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: "put"
        }

        if (formData.name.length < 1) {
            $("#editCategoryModal input#edit_name").addClass("is-invalid")
            $("#edit_name_error").text("Nama tidak boleh kosong!")
            isError = isError || true
        }

        if (!isError) {
            $.ajax({
                type: "POST",
                url: "category/" + $(this).data('id'),
                data: formData,
                success: function (result) {
                    if (result.success) {
                        VanillaToasts.create({
                            text: 'Data kategori berhasil diedit!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                        // clearAddCategoryForm()
                        $("#editCategoryModal").modal('hide')
                    } else {
                        VanillaToasts.create({
                            text: 'Data kategori gagal diedit!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                    }
                    category_datatables.ajax.reload()
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: 'Data kategori gagal diedit',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })


    $("#editCategoryModal input#edit_name").on('blur', function () {
        if ($(this).val().length > 1) {
            $(this).removeClass("is-invalid")
            $("#edit_name_error").text("")
        }
    })

    function clearAddCategoryForm() {
        $("#addCategoryForm input#name").val("")
    }
})
