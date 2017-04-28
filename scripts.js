function funcGET(){
    var search = $("#txt_search").val();
    $.ajax({
        url: 'api.php/users/' + search,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            alert("right");
            alert(response);
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
