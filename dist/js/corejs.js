function custom_submit_form(form_id,button_action,button_id){
    $.ajax({
        type: 'POST',
        url: 'action.php?fun='+button_action,
        dataType: "json",
        data: $('#'+form_id).serialize() + "&button_id=" + button_id,
        
        success: function(data){
            console.log(data);
            showtoastr('toast-top-right', data.type, data.title, data.msg);
            if (data.redirect+'' !== 'undefined'){
                window.location.href = data.redirect;
            }
            
        }
    });
}

function submit_form(form_id,dowhat,target,button_action,button_id){
    // console.log($(form_id));
    $.ajax({
        type: 'POST',
        url: 'action.php',
        dataType: "json",
        data: $('#'+form_id).serialize() + "&dowhat=" + dowhat + "&target=" + target + "&button_action=" + button_action + "&button_id=" + button_id,
        
        success: function(data){
            //console.log(data);
            window.location.href = data.redirect;
        }
    });
}

function submit_form_wizard(form_id){
    // console.log($(form_id));
    var frmData = $(form_id).serialize();
    $.ajax({
        type: 'POST',
        url: 'action.php',
        dataType: "json",
        data: frmData + "&dowhat=save_form_wizard",        
        success: function(data){
            //console.log(data);
            window.location.href = data.redirect;
        }
    });
}

function delete_list_page_record(form_id,dowhat,button_id, $tbl_name, $return_to){    
    var pkeylist = new Array();
    $('input[class=listCheckbox]:checked').each(function(i){ pkeylist.push(this.value); });
    $.ajax({
        type: 'POST',
        url: 'action.php',
        dataType: "json",
        data: $('#'+form_id).serialize() + "&dowhat=" + dowhat + "&button_id=" + button_id + "&pkeylist=" + pkeylist + "&tbl_name=" + $tbl_name + "&return_to=" + $return_to,
        
        success: function(data){
            //console.log(data);
            window.location.href = data.redirect;
        }
    });
    console.log(pkeylist);
   
}

function showtoastr(position, type, title, msg){
    toastr.options = {
        closeButton: false,
        debug: false,
        progressBar: false,
        positionClass: position,
        onclick: null,
        newestOnTop: false,  // 最新一筆顯示在最上面
        preventDuplicates: false, // 隱藏重覆訊息
        showDuration: "300", // 顯示時間(單位: 毫秒)
        hideDuration: "1000", // 隱藏時間(單位: 毫秒)
        timeOut: "5000", // 當超過此設定時間時，則隱藏提示訊息(單位: 毫秒)
        extendedTimeOut: "1000", // 當使用者觸碰到提示訊息時，離開後超過此設定時間則隱藏提示訊息(單位: 毫秒)
        showEasing: "swing", // 顯示動畫時間曲線
        hideEasing: "linear", // 隱藏動畫時間曲線
        showMethod: "fadeIn", // 顯示動畫效果
        hideMethod: "fadeOut" // 隱藏動畫效果
    };

    toastr[type](msg, title);
}

// function initDataTable(pageArr, tableName, colName, prefix){
    
// }