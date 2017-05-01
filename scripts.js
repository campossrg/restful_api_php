function funcGET(){
    var search = $("#txt_search").val();

    $.ajax({
        url: 'api.php/users/' + search,         // the value to search on url
        type: 'GET',
        dataType: 'json',
        async: false,
        complete: function(data) {
            data_array = data.responseText.split("\n");     // retrieve an array with results
            data_array.pop();                               // last one is empty
            $('#sel_results option').remove();
            for(i=0; i<data_array.length; i++){
                $('#sel_results').append($('<option>', {
                    value: i,
                    text: data_array[i]
                }));
            }
            $("#sel_results").attr('size', data_array.length);              // change size
            $("#sel_results option:first").attr('selected', 'selected');    // set selected
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
                funcGET();                  // refresh data
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
            funcGET();                      // refresh data
        }
    })
}

function funcPUT(){
    var selected = $("#sel_results option:selected").text();
    var data = JSON.stringify(prompt("Inserted the desired value for selected item:", selected));

    $.ajax({
        url: 'api.php/users/' + selected,       // old value
        type: 'PUT',
        data: data,                             // modified value
        contentType: 'application/json',
        dataType: 'json',
        async: false,
        complete: function(data){
            funcGET();                          // refresh data
        }
    })
}
