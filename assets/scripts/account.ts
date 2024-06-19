const CREATE = 0;
const UPDATE = 1;
var operation = CREATE;
var table, editorCont, editor, slctCategory;

$(document).ready(e=>{
    table = $("#table tbody");
    editorCont = $("#editor_container");
    editor = $("#editor");
    slctCategory = $("#editor #categoryId");

    refreshTable(); // Auto refresh @ start.
    $("#refresh_btn").click(refreshTable);

    $("#create_btn").click(e=>{
        operation = CREATE;
        editor.find("input").each((idx, ipt)=>{
            $(ipt).val("");
        });
        refreshCategories();
        editorCont.show();
    });
    
    $("#save_btn").click(e=>{
        let url = operation == CREATE ? "crud/create.php" : "crud/update.php";
        $.ajax({
            url: url,
            method: "POST",
            data: editor.serialize(),
            dataType: "JSON",
            success: payload=>{
                if(payload.state) {
                    refreshTable();
                }

                operation = CREATE;
                editorCont.hide();
                alert(payload.content);
            }
        });
    });
});

function refreshTable() {
    $.ajax({
        url: "crud/read.php",
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
                    row.append( $(`<td>${data.Shortcode}</td>`) );
                    row.append( $(`<td>${data.Title}</td>`) );
                    row.append( $(`<td>${data.Category}</td>`) );
                    row.append( $(`<td>${data.AccountNumber}</td>`) );
                    row.append( $(`<td>${data.BankIcon}</td>`) );
                    row.append( $(`<td>${data.Status}</td>`) );

                    // ACTIONS
                    var actions = $("<td>");
                    row.append(actions);
                    
                    var updateBtn = $("<button>Update</button>");
                    actions.append(updateBtn);
                    updateBtn.click(e=>{
                        updateBtnClicked(data);
                    });

                    var deleteBtn = $("<button>Delete</button>");
                    actions.append(deleteBtn);
                    deleteBtn.click(e=>{
                        deleteBtnClicked(data.Id);
                    });
                });
            }
            else
            {
                table.append(`<td colspan="4" class="centered">Oops! ${content}</td>`);
            }
        }
    });
}

function refreshCategories(value) {
    slctCategory.prop('disabled', true);
    slctCategory.html(`<option value="-1">Loading...</option>`);
    $.ajax({
        url: "../category/crud/read.php",
        method: "GET",
        dataType: "JSON",
        success: payload => {
            let content = payload.content;
            if(payload.state) {
                slctCategory.prop('disabled', false);
                slctCategory.html(`<option value="0">--Select--</option>`);
                content.forEach(c => {
                    let opt = $(`<option value="${c.Id}">${c.Title}</option>`);
                    slctCategory.append(opt);
                });
                
                if(value) {
                    slctCategory.val(value);
                }
            } else {
                alert(content);
            }
        }
    });
}

function updateBtnClicked(data) {
    operation = UPDATE;
    editor.find("input").each((idx, ipt)=>{
        ipt = $(ipt);
        let name = ipt.attr("name");
        ipt.val( data[name] );
    });
    refreshCategories(data.CategoryId);
    editorCont.show();
}

function deleteBtnClicked(id) {
    $.ajax({
        url: "crud/delete.php",
        method: "POST",
        data: { Id: id },
        dataType: "JSON",
        success: payload=>{
            if(payload.state) {
                refreshTable();
            }

            alert(payload.content);
        }
    });
}