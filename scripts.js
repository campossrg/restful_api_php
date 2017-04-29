function funcGET(){
    var search = $("#txt_search").val();
    $.ajax({
        url: 'api.php/users/' + search,
        type: 'GET',
        dataType: 'json',
        async: false,
        complete: function(data) {
            data_array = data.responseText.split("\n");
            data_array.pop();
            $('#cls_results option').remove();
            for(i=0; i<data_array.length; i++){
                $('#cls_results').append($('<option>', {
                    value: i,
                    text: data_array[i]
                }));
            }
            $("#cls_results").attr('size', data_array.length);
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
