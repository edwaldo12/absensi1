
$(function () {
    $("#btnSaveAttendance").on('click',function(){
        let studentAttendance = []
        let attendanceTable = $("#attendanceTable>tbody>tr")
        attendanceTable.each(function(){
            let checkBox = $(this).children().first().children().first()
            studentAttendance.push({
                is_present : checkBox.prop('checked'), 
                student_id :checkBox.data('id')
            })
        })
        let formData = {
            studentAttendance : studentAttendance,
            _token : $("meta[name=csrf-token]").attr('content')
        }
        $.ajax({
            type : "POST",
            url : $(this).data('id'),
            data : formData,
            success : function(result){
                if (result.success) {
                    if(result.has_attendance){
                        $("input#attendance_status").val("Sudah di absen")
                    }
                    $("#attendanceTable>tbody").html("")
                    result.data.forEach(e=>{
                        $("#attendanceTable>tbody").append(
                            "<tr>"+
                                "<td>"+
                                    "<input type='checkbox' "+(e.is_present ? "checked" : "")+" data-id='"+e.id+"'>"+
                                "</td>"+
                                "<td>"+e.name+"</td>"+
                                "<td>"+(e.attendances.length > 0 ? new Date(e.attendances[0].updated_at).toISOString().split('GMT')[0].substring(0, 19).replace("T"," ") : "-")+"</td>"+
                            "</tr>"
                        )
                    })
                    VanillaToasts.create({
                        text: 'Absensi berhasil disimpan',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                } else {
                    VanillaToasts.create({
                        text: 'Absensi gagal disimpan',
                        positionClass: 'topRight',
                        timeout: 3000
                    });
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                VanillaToasts.create({
                    text: 'gagal menambahkan absen!',
                    positionClass: 'topRight',
                    timeout: 3000
                });
            }
        });
    })
    $("#check-all").on('click',function(){
        let checked = $(this).prop('checked')
            let a = $("#attendanceTable>tbody").children().each(function(e){
                $(this).children().first().children().first().prop('checked',checked)
            })
    })
})
