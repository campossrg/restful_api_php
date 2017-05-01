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
            $('#sel_results option').remove();
            for(i=0; i<data_array.length; i++){
                $('#sel_results').append($('<option>', {
                    value: i,
                    text: data_array[i]
                }));
            }
            $("#sel_results").attr('size', data_array.length);
            $("#sel_results option:first").attr('selected', 'selected');
        }
    });
}

function funcPOST(){
    var data = $("#txt_search").val();     //validate empty value
    if(data){
        data = JSON.stringify(data);

        $.ajax({
            url: 'api.php/users',
            type: 'POST',
            data: data,
            contentType: 'application/json',
            dataType: 'json',
            async: false,
            complete: function(data) {
                console.log(data.responseText);
                funcGET();
            }
        });
    }

}

function funcDELETE(){
    var data = JSON.stringify($("#sel_results option:selected").text());

    $.ajax({
        url: 'api.php/users',
        type: 'DELETE',
        data: data,
        contentType: 'application/json',
        dataType: 'json',
        async: false,
        complete: function(data){
            console.log(data.responseText);
            funcGET();
        }
    })
}

function funcPUT(){
    var selected = $("#sel_results option:selected").text();
    var data = JSON.stringify(prompt("Inserted the desired value for selected item:", selected));

    $.ajax({
        url: 'api.php/users/' + selected,
        type: 'PUT',
        data: data,
        contentType: 'application/json',
        dataType: 'json',
        async: false,
        complete: function(data){
            console.log(data.responseText);
            funcGET();
        }
    })
}
