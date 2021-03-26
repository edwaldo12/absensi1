$(function(){
    $("#btnTeacherReport").on('click',function(){
        // Open new window 
        let reportPage = window.open()
        //  Report Teacher Table
        let reportTeacherTable = $("#card-content")
        reportTeacherTable.children().first().attr("border","1")
        // reportTeacherTable.children().first().css("min-width","auto")
        // Title element
        let titleElement = $("<h3>Laporan Mengajar Guru</h3>")
        titleElement.css("text-align","center")
        // Write & Print
        reportPage.document.write($("<div>").append(titleElement).html())
        reportPage.document.write(reportTeacherTable.html())
        reportPage.document.close()
        reportPage.focus()
        reportPage.print()
    });
})