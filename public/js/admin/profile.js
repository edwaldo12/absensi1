$(function () {
    $("#btnSaveAccount").on('click', function () {
        let isError = false
        let formData = {
            name: $("#accountForm input#name").val(),
            username: $("#accountForm input#username").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        }

        if (formData.name.length < 1) {
            $("#accountForm input#name").addClass("is-invalid")
            $("#name_error").text("Nama tidak boleh kosong!")
            isError = isError || true
        }
        if (formData.username.length < 1) {
            $("#accountForm input#username").addClass("is-invalid")
            $("#username_error").text("Username tidak boleh kosong!")
            isError = isError || true
        }
        if (!isError) {
            $.ajax({
                type: "POST",
                url: "profile/update_account",
                data: formData,
                success: function (result) {
                    $("#adminName").text(result.name)
                    VanillaToasts.create({
                        text: '<i class="fa fa-user mr-2"></i> Berhasil memperbarui profile!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: '<i class="fa fa-close mr-2"></i> Terjadi kesalahan pada server!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })

    $("#accountForm input#name").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#name_error").text("")
        }
    })

    $("#accountForm input#username").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#username_error").text("")
        }
    })


    $("#btnChangePassword").on('click', function () {
        let isError = false
        let formData = {
            current_password: $("#changePasswordForm input#current_password").val(),
            new_password: $("#changePasswordForm input#new_password").val(),
            confirmation_new_password: $("#changePasswordForm input#confirmation_new_password").val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        }

        if (formData.current_password.length < 1) {
            $("#changePasswordForm input#current_password").addClass("is-invalid")
            $("#current_password_error").text("Silahkan diisi!")
            isError = isError || true
        }
        if (formData.new_password.length < 1) {
            $("#changePasswordForm input#new_password").addClass("is-invalid")
            $("#new_password_error").text("Silahkan diisi!")
            isError = isError || true
        }
        if (formData.confirmation_new_password.length < 1) {
            $("#changePasswordForm input#confirmation_new_password").addClass("is-invalid")
            $("#confirmation_new_password_error").text("Silahkan diisi!")
            isError = isError || true
        }
        if (formData.new_password != formData.confirmation_new_password) {
            $("#changePasswordForm input#confirmation_new_password").addClass("is-invalid")
            $("#confirmation_new_password_error").text("Password tidak cocok!")
            isError = isError || true
        }
        if (!isError) {
            $.ajax({
                type: "POST",
                url: "profile/change_password",
                data: formData,
                success: function (result) {
                    if (result.success) {
                        VanillaToasts.create({
                            text: '<i class="fa fa-close mr-2"></i> Password berhasil diperbarui!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                        $("#changePasswordForm input#current_password").val("")
                        $("#changePasswordForm input#new_password").val("")
                        $("#changePasswordForm input#confirmation_new_password").val("")
                    } else {
                        VanillaToasts.create({
                            text: '<i class="fa fa-close mr-2"></i> Password tidak sesuai!',
                            positionClass: 'topRight',
                            timeout: 3000
                        });
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    VanillaToasts.create({
                        text: '<i class="fa fa-close mr-2"></i> gagal mengubah data pengguna!',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            })
        }
    })


    $("#changePasswordForm input#current_password").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#current_password_error").text("")
        }
    })
    $("#changePasswordForm input#new_password").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#new_password_error").text("")
        }
    })
    $("#changePasswordForm input#confirmation_new_password").on('blur', function () {
        if ($(this).val().length > 0) {
            $(this).removeClass("is-invalid")
            $("#confirmation_new_password_error").text("")
            if ($("#changePasswordForm input#new_password").val() != $(this).val()) {
                $("#changePasswordForm input#confirmation_new_password").addClass("is-invalid")
                $("#confirmation_new_password_error").text("Password tidak cocok!")
            }
        }
    })

})