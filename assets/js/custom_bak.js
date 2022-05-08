function getDetailPageData_bak(tbl, colArr, detailArr, colName, pkey) {
    $.ajax({
        method: "post",
        url: "process.php",
        data: { part: 'get_detail_data', tableName: tbl, colName:colName, pkey:pkey },
        dataType: "JSON",
        success: function(response) {
            var dataArr = response.data;
            setDetailPageData(dataArr, colArr, detailArr);
            $('#table .editable').editable('toggleDisabled');
        },
        error: function() {
            alert('Cannot get data!');
        }
    });
}

function getTableData_bak(tableName, colArr) {
    var dataArr = [];
    $.ajax({
        method: "post",
        url: "process.php",
        data: { part: 'get_data', tableName: tableName },
        dataType: "JSON",
        async:false,        
        success: function(response) {
            dataArr = response.data;
            setTableData(dataArr, colArr);

            //if (tbl+'' == 'tbl_course') updateCourseRegistered(tbl, dataArr);

            $('#table .editable').editable('toggleDisabled');
        },
        error: function() {
            alert('Cannot get data!');
        }
    });

    return dataArr;
}

