function funcGET(){
    var search = $("#txt_search").val();
    $.ajax({
        url: 'api.php/users/' + search,
        type: 'GET',
        dataType: 'json',
        async: false,
        complete: function(data) {
            $("#txt_results").val(data.responseText);
            no_lines = data.responseText.split("\n").length - 1; //avoid last one
            $("#txt_results").attr('rows', no_lines); //modify no of rows
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
        complete: function(data) {
            alert(data.responseText);
        }
    });
}
