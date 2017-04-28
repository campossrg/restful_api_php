function funcGET(){
    var search = $("#txt_search").val();
    $.ajax({
        url: 'api.php',
        type: 'GET',
        data: search,
        dataType: 'json',
        async: false,
        success: function(search) {
            alert("search");
        }
    });
}

function funcPOST(){
    var data = JSON.stringify($("#txt_name").val());
    $.ajax({
        url: 'api.php/users',
        type: 'POST',
        data: data,
        contentType: 'application/json',
        dataType: 'json',
        async: false,
        success: function(data) {
            return data;
        }
    });
}
