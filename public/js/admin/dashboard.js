$(function(){
    $("#today_table").on('click','.btn-open-class',function(){
        $("#addClassModal select#schedule_id").select2("val",$(this).data('schedule-id').toString());
        $("#addClassModal select#time_id").select2("val",$(this).data('time-id').toString());
        $("#addClassModal").modal('show')
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
    
    function clearAddClassForm() {
        $("#addClassForm select#teacher_id option:selected").prop('selected', false)
        $("#addClassForm select#teacher_id>:first-child").prop('selected', true)
        $("#addClassForm select#schedule_id option:selected").prop('selected', false)
        $("#addClassForm select#schedule_id>:first-child").prop('selected', true)
        $("#addClassForm input#start_time").val("")
        $("#addClassForm input#end_time").val("")
    }
})