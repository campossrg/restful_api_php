
function getJSON(){
    var search = JSON.stringify($("#txt_search").val());
    $.ajax({
        url: 'api.php/users',
        type: 'GET',
        data: search,
        contentType: 'application/json',
        dataType: 'json',
        async: false,
        success: function(search) {
            return search;
        }
    });
}

function sendJSON(){
    var data = JSON.stringify($("#form_data").serializeArray());
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
