$(function(){
    $("#btnStudentReport").on('click',function(){
        // Open new window 
        let reportPage = window.open()
        //  Report Student Table
        let reportStudentTable = $("#card-content")
        reportStudentTable.children().first().attr("border","1")
        // reportStudentTable.children().first().css("min-width","auto")
        // Title element
        let titleElement = $("<h3>Laporan Detail Absensi Siswa</h3>")
        titleElement.css("text-align","center")
        // Write & Print
        reportPage.document.write($("<div>").append(titleElement).html())
        reportPage.document.write(reportStudentTable.html())
        reportPage.document.close()
        reportPage.focus()
        reportPage.print()
    });
})