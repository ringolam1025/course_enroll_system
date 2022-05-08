function setSelectedButtonStatus(action){
    if (action+'' == 'disable'){
        $('#btnDeleteSelected').addClass('disabled');
    
    }else{
        $('#btnDeleteSelected').removeClass('disabled');
    }
}

function operateFormatter(value, row, index) {
    if(typeof(detailPage) == "undefined" || detailPage == null){
        return ['<a class="remove" href="javascript:void(0)" title="Remove" style="color:red;">',
                '<i class="fa fa-trash-alt"></i>',
                '</a>'
                ].join('');
    }else{
        return ['<a class="remove" href="javascript:void(0)" title="Remove" style="color:red;">',
                '<i class="fa fa-trash-alt"></i>',
                '</a>&nbsp;&nbsp;',
                '<a class="edit" href="javascript:void(0)" title="Edit" style="color:blue;">',
                '<i class="far fa-eye"></i>',
                '</a>'
                ].join('');
    }    
}

window.operatEvents = {
    'click .remove': function(e, value, row, index) {
        var selected = $.parseJSON(JSON.stringify(row));
        var keyVal = "";

        if (pageType+'' == 'detail') {
            keyVal = pageArr.SubKeyCol;
        }else{
            keyVal = pageArr.pkeyCol;
        }

        selectedArr = selected[keyVal];
        $("#deleteItem").val(selectedArr);
        $("#recordList").html(selectedArr);
        $('#deleteModal').modal('toggle');
    },
    'click .edit': function(e, value, row, index) {
        var selected = $.parseJSON(JSON.stringify(row));
        var id = selected[pageArr.pkeyCol];
        window.open(detailPage+'.php?'+(pageArr.pkeyCol)+'='+id, '_self'); 
    },
    'click .showView': function(e, value, row, index) {
        var selected = $.parseJSON(JSON.stringify(row));
        //var id = selected[colName];
        //window.open('student_enroll.php?'+colName+'='+id, '_self'); 
    },
    'click .hideView': function(e, value, row, index) {
        var selected = $.parseJSON(JSON.stringify(row));
        var id = selected[pageArr.pkeyCol];
        //window.open('student_enroll.php?'+colName+'='+id, '_self'); 
    }
}

function changeEditModeStatus(current_status) {
    if (current_status == 'close') {
        $('#enable span').text("Disable Edit Mode");
    } else {
        $('#enable span').text("Enable Edit Mode");
    }
    $('#table .editable').editable('toggleDisabled');
}

function checkEditModeStatus() {
    var current_status = "";
    var spanText = $('#enable span').text();

    if (spanText + '' == 'Disable Edit Mode') {
        current_status = 'open';
    } else {
        current_status = 'close';
    }
    return current_status;
}

function checkArrExist(arr , key){
    var flag = true;
    for (var i=0;i<arr.length;i++) {
        if (arr[i]['field']+'' == key){
            flag = false;
        }
    }
    return flag;
}

function checkArrExist2(arr , key){
    var flag = false;
    for (var i=0;i<arr.length;i++) {
        if (arr[i].hasOwnProperty(key)){
             flag = true;
        }
    }
    return flag;
}

function linkFormatter(value, row, index){
    var output = "";
    if (typeof value !== 'undefined'){
        output = value;
    }else{
        output = 0;
    }
    return "<a href='enrolled_student.php?offerID="+row.offerID+"'>"+output+"</a>";
}

function dateFormatter(value, row, index) {
    var output = "";
    output = moment(parseInt(value.$date.$numberLong)).format('YYYY-MM-DD');
    return output;

}

function dateTimeFormatter(value, row, index) {
    var output = "";
    output = moment(parseInt(value.$date.$numberLong)).format('YYYY-MM-DD h:m:s');
    return output;

}


function checkActionButton(colArr){
    var user_type = "";
    $.ajax({
        url: 'process.php',
        type: 'post',
        dataType: 'json',
        async:false,
        data: {part: 'get_session'},
        success: function(response) {
            user_type = response.data.user_type;
        }
    });
    // console.log("checkActionButton", colArr);
    if (user_type+'' != 'student'){    
        if (checkArrExist(colArr, 'operate')){
            colArr.unshift({
                         "field": "operate",
                         "title": "",
                         "events": "operatEvents",
                         "formatter" : "operateFormatter",
                         "clickToSelect": "0",
                         "width":"70",
                         "align":"center"
                        });   
             
        }    
        if (checkArrExist(colArr, 'action')){
            colArr.unshift({
                        "field": "action",
                        "title": "",
                        "checkbox":"1"
                        });   
        }
    }
    return colArr;    
}
function getEnrolledStudent(get_pkey, tableColArr, pageArr){
    var dataArr = [];    
    // console.log("Col List", tableColArr);
    // console.log("Page's json", pageArr);
    $.ajax({
        method: "post",
        url: "process.php",
        data: { part: 'getEnrolledStudent',offerID:get_pkey},
        dataType: "JSON",
        async:false,
        success: function(response) {
            dataArr = response.data;
            //console.log(dataArr);

            pageTitleHTML = $('#page_title').html();
            pageTitleHTML = pageTitleHTML.replace("{YEAR}", dataArr.year);
            pageTitleHTML = pageTitleHTML.replace("{COURSEID}", "<span style='font-weight:bold;font-style:italic;'>"+dataArr.title+"</span>");
            $('#page_title').html(pageTitleHTML);

            setDetailPageData(dataArr, tableColArr, detailArr);
            setDetailPageTable(tableColArr, 'tbl_student', 'student_id', dataArr.detailPage);
            //$('#table .editable').editable('toggleDisabled');
        },
        error: function() {
            alert('Cannot get data! - getTableData()');
        }
    });
    return dataArr;
}

function getTableData(tableColArr, pageArr){
    var dataArr = [];
    // console.log("Col List", tableColArr);
    // console.log("Page's json", pageArr);

    $.ajax({
        method: "post",
        url: "process.php",
        data: { part: 'get_data', tableName: pageArr.tableName, pageArr:pageArr },
        dataType: "JSON",
        async:false,
        success: function(response) {
            dataArr = response.data;
            //console.log(dataArr);
            setTableData(dataArr, tableColArr, pageArr);
            $('#table .editable').editable('toggleDisabled');
        },
        error: function() {
            alert('Cannot get data! - getTableData()');
        }
    });
    return dataArr;
}

function setTableData(dataArr, tableColArr, pageArr){
    // Add for checkbox and button
    tableColArr = checkActionButton(tableColArr);
    // Set Edit array
    editArr = {"tableName":pageArr.tableName,"pkeyVal":pageArr.pkeyCol};
    initDataTable(dataArr, tableColArr, editArr);
}

function getOfferDetails(tableName, unwind, condition) {
    $.ajax({
        method: "post",
        url: "process.php",
        async:false,
        data: { part: 'custSearch', tableName: tableName, unwind:unwind, condition:condition },
        dataType: "JSON",
        success: function(response) {
            dataArr = response.data; 
            setTableData(dataArr, tableColArr, pageArr);
            $('#table .editable').editable('toggleDisabled');
        },
        error: function() {
            alert('Cannot get data! - getOfferDetails()');
        }
    });
}

function getStudentEnroll(pageArr){
    var dataArr = [];
    $.ajax({
        method: "post",
        url: "process.php",
        async:false,
        data: { part: 'get_student_enroll', tableName: pageArr.tableName, colName:pageArr.pkeyCol, get_pkey:get_pkey, pageArr:pageArr },
        dataType: "JSON",
        success: function(response) {
            dataArr = response.data;
            setDetailPageData(dataArr, pageArr.tableList, detailArr);
            setStudentEnrollDetailTable(tableColArr, pageArr.tableName, pageArr.keyCol, dataArr.enrolled);

            $('#table .editable').editable('toggleDisabled');
        },
        error: function() {
            alert('Cannot get data! - getDetailPageData()');
        }
    });
    
    return dataArr;
}

function getDetailPageData(tableColArr, pageArr) {    
    var dataArr = [];
    //console.log("Col List", tableColArr);
    //console.log("Page's json", pageArr);

    $.ajax({
        method: "post",
        url: "process.php",
        async:false,
        data: { part: 'get_detail_data', tableName: pageArr.tableName, colName:pageArr.pkeyCol, get_pkey:get_pkey, pageArr:pageArr },
        dataType: "JSON",
        success: function(response) {
            dataArr = response.data;
            setDetailPageData(dataArr, pageArr.tableList, detailArr);
            setDetailPageTable(tableColArr, pageArr.tableList, pageArr.pkeyCol, dataArr[0].enrolled);

            $('#table .editable').editable('toggleDisabled');
        },
        error: function() {
            alert('Cannot get data! - getDetailPageData()');
        }
    });
    return dataArr;
}

function setDetailPageData(dataArr, colArr, detailArr){
    var _listArr = dataArr;    
    // ------ Set details list
    for (var i=0; i<detailArr.length;i++) {
        var field = detailArr[i].field;
        var db_type = detailArr[i].db_type;

        if (db_type+'' == 'date'){
            //var tmp = new Date(_listArr[field]).toISOString().split('T')[0];
            var tmp = moment(_listArr[field]).format('YYYY-MM-DD');
            //var tmp = moment(parseInt(_listArr[field]).format('YYYY-MM-DD'));
            $('#'+field).val(tmp);

        }else{
            $('#'+field).val(_listArr[field]);
        }
    }    
}

function setDetailPageTable(tableColArr, tbl, keyCol, dataArr){
    // Add for checkbox and button
    tableColArr = checkActionButton(tableColArr);
    var tableName = tbl;
    var pkeyVal = keyCol;
    if (checkArrExist2(dataArr, 'studentID')){
        // -- Init Data Table
        editArr = {"tableName":tbl,"pkeyVal":pkeyVal};
        initDataTable(dataArr, tableColArr, editArr);
        //initDataTable(editArr);
    }    
}

function setStudentEnrollDetailTable(tableColArr, tbl, keyCol, detailArr){
    //console.log("setStudentEnrollDetailTable(): tableColArr",tableColArr);
    //console.log("setStudentEnrollDetailTable(): dataArr",dataArr);
    
    // Add for checkbox and button
    tableColArr = checkActionButton(tableColArr);
    //if (checkArrExist2(detailArr, 'studentID')){
    if (checkArrExist2(detailArr, 'offerID')){   
        editArr = {"tableName":tbl,"pkeyVal":keyCol};
        // -- Init Data Table
        initDataTable(detailArr, tableColArr, editArr);
    }
}

function initDataTable(dataArr, colArr, editArr){
    $.fn.editable.defaults.mode = 'inline';
    $.fn.editable.defaults.emptytext = '-';
    //console.log(dataArr); 

    //height: $(document).height()-100,
    $('#table').bootstrapTable('destroy');
    $('#table').bootstrapTable({        
        undefinedText: '-',
        silent: true,
        cookie: true,
        showColumns: true,
        multipleSelectRow: true,
        search: true,
        visiblesearch: true,
        pageSize: 25,
        toolbar: "#toolbar",
        clickToSelect: true,
        rememberOrder: true,
        pagination:"true",
        paginationHAlign: "left",
        paginationDetailHAlign:"right",
        data: dataArr,
        columns: colArr,
        onEditableSave: function(field, row, oldValue, $el) {
            var part = 'update';
            var tableName = editArr.tableName;
            var pkeyVal = editArr.pkeyVal;
            $.ajax({
                type: "post",
                url: "process.php",
                data: {part: part, row:row, pkeyVal:pkeyVal, tableName:tableName, colName: field, oldValue: oldValue, pageArr:pageArr},
                dataType: 'JSON',
                success: function(response){
                    //alert('Update successfully.');
                    //location.reload();
                },
                error: function() {
                    alert('Cannot get data! - initDataTable()');
                }
            });
        }
    });
    $('#table').bootstrapTable('hideLoading');
}

function searchArrInArr(tar, arr, col){    
    for (var y = 0; y < arr.length; y++) {
        if (tar == arr[y]['_id'][col]){
            //console.log("searchArrInArr(): ", "Target: "+ tar + ", Source Array: " + arr[y]['_id'] + ", index: " + y);
            return y;
        }
    }
    return -1;
}

function updateCourseRegistered(tbl, dataArr){
    var part = "getRegisterCount";
    var col = "offerID";
    var nestArr = '$enrolled';

    $.ajax({
        url: 'process.php',
        type: 'post',
        dataType: 'json',
        async:false,
        data: {part: part, tableName:tbl, col:col, nestArr:nestArr},
        success: function(response) {
            var data = response.data;
            var total = 0;            
            var tableData = $('#table').bootstrapTable('getData');

            for (var i = 0; i < tableData.length; i++) {
                var tmp = searchArrInArr(tableData[i][col], data ,'offerID');
                if(tmp >= 0){
                    $('#table').bootstrapTable('updateRow', {
                        index: i,
                        row: {
                            registered: data[tmp].count
                        }
                    });
                }
            }
        },
        error: function() {
            alert('Cannot get Cal!');
        }
    });
}

$('#enable').click(function() {
    var flag = checkEditModeStatus();
    changeEditModeStatus(flag);
    //test();
});

$('#table').on('page-change.bs.table', function(e, number, size) {
    var current_status = checkEditModeStatus();
    if (current_status == 'close') {
        $('#table .editable').editable('toggleDisabled');
    }
});

$('#table').on('check.bs.table', function(e, row, $element) {
    setSelectedButtonStatus('enable');
});

$('#table').on('uncheck.bs.table', function(e, row, $element) {
    setSelectedButtonStatus('disable');
});

$('#table').on('check-all.bs.table', function(e, row, $element) {
    setSelectedButtonStatus('enable');
});

$('#table').on('uncheck-all.bs.table', function(e, row, $element) {
    setSelectedButtonStatus('disable');
});

// $('#table').on('click-row.bs.table', function(e, row, $element, field) { //row, $element, field
//     console.log(row);
//     console.log($element);
//     console.log(field);
// });

$('#btnDeleteSelected').click(function() {
    //console.log("btnDeleteSelected:Click");
    var selected = $.parseJSON(JSON.stringify($('#table').bootstrapTable('getSelections')));
    var selectedArr = "";
    keyVal = "";

    if (pageType+'' == 'detail') {
        keyVal = pageArr.SubKeyCol;
    }else{
        keyVal = pageArr.pkeyCol;
    }

    for (var i = 0; i < selected.length; i++) {        
        selectedArr += ((selectedArr + '' != '') ? ',' : '') + selected[i][keyVal];
    }

    $("#deleteItem").val(selectedArr);
    $("#recordList").html(selectedArr);

    for (var i = 0; i < selected.length; i++) {
        selectedArr += ((selectedArr + '' != '') ? ',' : '') + keyVal;
    }
});

$("#newRecord").submit(function(e){
    e.preventDefault();
    var formData = $("#newRecord").serializeArray();    
    if (pageArr.pageType+'' != 'detail'){
        var part = "add";
        $.ajax({
            url: 'process.php',
            type: 'post',
            dataType: 'json',
            data: {part: part, tableName:pageArr.tableName, colName: pageArr.pkeyCol, prefix:pageArr.prefix, formData:formData},
            success: function(data) {
                //alert("Recrod created");
                // close the modal
                $("#addModal").modal('toggle');
                $('#successModal').modal('toggle');
                $('#table').bootstrapTable('showLoading');
                $('#table').bootstrapTable('destroy');
                $('#table').bootstrapTable('hideLoading');
                getTableData(pageArr.tableList, pageArr);
            }
        });
    }else if (pageArr.page_id+'' == 'enrolled_student'){    
        var part = "enroll_student";
        $.ajax({
            url: 'process.php',
            type: 'post',
            dataType: 'json',
            data: {part: part, tableName:pageArr.tableName, colName: pageArr.pkeyCol, prefix:pageArr.prefix, formData:formData},
            success: function(response) {
                var dataArr = response;
                $("#addModal").modal('toggle');
                if (dataArr.flag+'' == 'success'){                    
                    $('#successModal').modal('toggle');
                    $('#table').bootstrapTable('showLoading');
                    $('#table').bootstrapTable('destroy');
                    $('#table').bootstrapTable('hideLoading');
                    getEnrolledStudent(get_pkey, tableColArr, pageArr);
                }else{
                    $('#failModal').modal('toggle');
                    $('#failModal_msg').html(dataArr.msg);
                }
                
            }
        });
    }
});
  
$("#delRecord").submit(function(e){
    e.preventDefault();
    var formData = $("#delRecord").serializeArray();
    var part = "";
    var keyVal = "";
    
    if (pageType+'' == 'detail') {
        keyVal = pageArr.SubKeyCol;
        part = "detailPage_delete";
        $.ajax({
            url: 'process.php',
            type: 'post',
            dataType: 'json',
            data: {part: part, tableName:pageArr.tableName, formData:formData, pkeyVal:keyVal, get_pkey:get_pkey, pageArr:pageArr},
            success: function(data) {
                //alert("Recrod deleted");
                // close the modal
                $('#deleteModal').modal('toggle');
                $('#successModal').modal('toggle');

                $('#table').bootstrapTable('showLoading');
                $('#table').bootstrapTable('destroy');
                $('#table').bootstrapTable('hideLoading');

                if (pageArr.page_id+'' == 'enrolled_student'){
                    getEnrolledStudent(get_pkey, pageArr.tableList, pageArr);
                    
                }else{
                    getStudentEnroll(pageArr);
                }
            }
        });

    }else{
        keyVal = pageArr.pkeyCol;
        part = "delete";
        $.ajax({
            url: 'process.php',
            type: 'post',
            dataType: 'json',
            data: {part: part, tableName:pageArr.tableName, formData:formData, pkeyVal:keyVal},
            success: function(response) {
                var dataArr = response;
                // close the modal
                $('#deleteModal').modal('toggle');
                if (dataArr.flag+'' == 'success'){                    
                    $('#successModal').modal('toggle');
                    $('#table').bootstrapTable('showLoading');
                    $('#table').bootstrapTable('destroy');
                    getTableData(pageArr.tableList, pageArr);
                    $('#table').bootstrapTable('hideLoading');

                }else{
                    $('#failModal').modal('toggle');
                    $('#failModal_msg').html(dataArr.msg);
                }
                
                
            }
        });
    }
});

$("#search").submit(function(e){
    e.preventDefault();
    var formData = $("#search").serializeArray();
    var part = "search";

    $.ajax({
        url: 'process.php',
        type: 'post',
        dataType: 'json',
        data: {part: part, tableName:pageArr.tableName, formData:formData, pageArr:pageArr},
        success: function(response) {
            var dataArr = response.data;
            //alert("Submit Search");
            $('#table').bootstrapTable('showLoading');
            $('#table').bootstrapTable('destroy');
            $('#table').bootstrapTable('hideLoading');
            //getTableData(tableName, pageArr);
            //setTableData(response.data, pageArr);
            setTableData(response.data, tableColArr, pageArr);
            if (pageArr.tableName+'' == 'tbl_offer') updateCourseRegistered('tbl_student', dataArr);
        }
    });
    var flag = checkEditModeStatus();
    //if (flag == 'close') {    
    $('#table .editable').editable('toggleDisabled');
    //}
});

$('#btnBack').click(function() {
    window.history.back();
}); 

$('#btnResetSearch').click(function (){
    $('#search').find("input,textarea,select")
                   .val('')
                   .end()
                .find("input[type=checkbox], input[type=radio]")
                   .prop("checked", "")
                   .end();
    $('.basic-multiple').val(null).trigger('change');    
});



/* ============================================== */
function testEditMode(){
    var tmp = JSON.stringify($('#table').bootstrapTable('getOptions').editable);
    console.log("Current:"+tmp);


    if (tmp){
        // $('#table').editable('destroy');
        // $('#table').bootstrapTable('refreshOptions', {editable: false});
        //$('#table').editable('toggleDisabled');
        var tmp = JSON.stringify($('#table').bootstrapTable('getOptions').editable);
        console.log("After:"+tmp);
    }else{
        //$('#table').bootstrapTable('refreshOptions', {editable: true});
        //$('#table').editable('toggleDisabled');
        //$('#table').editable('option', 'disabled', true);
        var tmp = JSON.stringify($('#table').bootstrapTable('getOptions').editable);
        console.log("After 2:"+tmp);
    }

    $('#table').editable('toggleDisabled');
}

