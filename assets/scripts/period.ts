var table, editorCont, editor;

$(document).ready(e=>{
    table = $("#table tbody");
    editorCont = $("#editor_container");
    editor = $("#editor");

    refreshTable(); // Auto refresh @ start.
    $("#refresh_btn").click(refreshTable);

    $("#open_btn").click(e=>{
        editor.find("input").each((idx, ipt)=>{
            $(ipt).val("");
        });
        editorCont.show();
    });
    
    $("#save_btn").click(e=>{
        $.ajax({
            url: "open.php",
            method: "POST",
            data: editor.serialize(),
            dataType: "JSON",
            success: payload=>{
                if(payload.state) {
                    refreshTable();
                }

                editorCont.hide();
                alert(payload.content);
            }
        });
    });
});

function refreshTable() {
    $.ajax({
        url: "read.php",
        method: "GET",
        dataType: "JSON",
        success: payload=>{
            table.empty();
            
            let content = payload.content;
            if(payload.state) {
                $.each(content, (idx, data)=>{
                    let row = $("<tr>");
                    table.append(row);
                    
                    // FIELDS
                    row.append( $(`<td>${data.Id}</td>`) );
                    row.append( $(`<td>${data.FromDate}</td>`) );
                    row.append( $(`<td>${data.ToDate}</td>`) );
                    row.append( $(`<td>${data.Status}</td>`) );
                });
            }
            else
            {
                table.append(`<td colspan="4" class="centered">Oops! ${content}</td>`);
            }
        }
    });
}