<?php

    $method = $_SERVER['REQUEST_METHOD'];                               // METHOD
    $request = explode('/', trim($_SERVER['REQUEST_URI'], '/') );       // URL INFO
    echo $_SERVER['REQUEST_URI'] "\n";
    echo array_shift($request);                                              // POP 1ST
    $input = json_decode(file_get_contents('php://input'), true);       // RETRIEVE POST DATA

    //DATABASE CONNECTION
    $host = "localhost";
    $dbname = "db_rest";
    $user = "root";
    $pass = "admin";
    $link = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

    // retrieve the table and key from the path
    $table = array_shift($request);
    echo "\n" . $table . "Que es esto \n";
    if($table === 'users'){
        $key = array_shift($request);
        if(empty($key)){
            echo "<br>KEY: empty<br>";
        }
        else{
            echo "<br>KEY: " . $key . "<br>";
        }
    } else {
        // Deny resources different to 'users'
        echo "Resource must be 'users'";
        header('HTTP/1.1 404 Not Found');
        exit;
    }

    // return an array with all the input´s values
    $values = array_map(function ($value) {
        if ($value['value']===null) return null;
        return $value['value'];
    },$input);
    echo "\n===" . json_encode($values) . "===\n";

    // build the SET part of the SQL command
    $set = $values[0];
    // for ($i=0;$i<count($columns);$i++) {
    //     $set.=($i>0?',':'').'`'.$columns[$i].'`=';
    //     $set.=($values[$i]===null?'NULL':'"'.$values[$i].'"');
    // }

    // create SQL based on HTTP method
    $result='';
    switch ($method) {                                          //Depending on the HTTP_REQUEST...
    case 'GET':
        $sql = "select * from $table".($key?" WHERE users_name='$key'":'');   //----------->>>WRONG OPTION
        $result = $link->query($sql);                                 // You must avoid the SQL injection hack
        break;
    case 'PUT':
        // $sentence = $link->prepare("update :table set :set where id= :key");
        // $result = $sentence->execute(array(':table' => $table, ':set' => $set, ':key' => $key));
        // break;
    case 'POST':
        $sentence = $link->prepare("insert into users (users_name) values (:set)");
        $result = $sentence->execute(array('set' => $set));
        break;
    case 'DELETE':
        // $sql = "delete `$table` where id=$key";
        // $sentence = $link->prepare("delete :table where id= :key");
        // $result = $sentence->execute(array(':table' => $table, ':key' => $key));
        // break;
    }

    // die if SQL statement failed
    if (!$result) {
        http_response_code(404);        //return 404 error page
        $link = null;                   //close conn
    }

    // print results, insert id or affected row count
    if ($method == 'GET') {                                       //GET
        if (!$key) echo '[<br>';
        foreach ($result as $row) {
            echo $row['users_name'] . "<br>";
        }
        if (!$key) echo ']';
    } elseif ($method == 'POST') {                                //POST
        echo $link->lastInsertId();                               // SQL insert "sentence"
    } else {
        echo $result->rowCount();                           // UPDATE or DELETE num of affected rows
    }
    // close mysql connection
    $link = null;                   //close conn
?>
