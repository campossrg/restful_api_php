<?php

    // get the HTTP method, path and body of the request
    $method = $_SERVER['REQUEST_METHOD'];                               // METHOD
    $request = explode('/', trim($_SERVER['REQUEST_URI'], '/') );       // URL INFO
    array_shift($request);                                              // POP 1ST
    echo json_decode(file_get_contents('php://input'));             // RETRIEVE POST DATA


    //DATABASE CONNECTION
    $host = "localhost";
    $dbname = "db_rest";
    $user = "root";
    $pass = "admin";
    $link = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

    // retrieve the table and key from the path
    $table = array_shift($request);
    if($table === 'users'){
        echo "TABLE: " . $table . "<br>";
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

    // escape the columns and values from the input object
    // $columns = preg_replace('/[^a-z0-9_]+/i','',array_keys($input));  //return all the keys of the input array
    $values = array_map(function ($value) {         //Crea un array con todos los valores obtenidos del input
        if ($value===null) return null;
        return (string)$value;
    },array_values($input));                                    //Devuelve el array indexado

    foreach ($value as $v ) {
        echo $v . "@<br>";
    }

    // build the SET part of the SQL command
    // $set = '';                                                  //non-easy to read SQL sentence
    // for ($i=0;$i<count($columns);$i++) {
    //     $set.=($i>0?',':'').'`'.$columns[$i].'`=';
    //     $set.=($values[$i]===null?'NULL':'"'.$values[$i].'"');
    // }
    // echo $set."<br>";

    // create SQL based on HTTP method
    $result='';
    switch ($method) {                                          //Depending on the HTTP_REQUEST...
    case 'GET':
        $sql = "select * from $table".($key?" WHERE users_name='$key'":'');   //----------->>>WRONG OPTION
        $result = $link->query($sql);                                 // You must avoid the SQL injection hack
        break;
    case 'PUT':
        $sentence = $link->prepare("update :table set :set where id= :key");
        $result = $sentence->execute(array(':table' => $table, ':set' => $set, ':key' => $key));
        break;
    case 'POST':
        echo "@POST works";
        // $sql = "insert into `$table` set $set"; 
        // $sentence = $link->prepare("insert into :table set :set");
        // $result = $sentence->execute(array(':table' => $table, ':set' => $set));
        break;
    case 'DELETE':
        $sql = "delete `$table` where id=$key"; 
        $sentence = $link->prepare("delete :table where id= :key");
        $result = $sentence->execute(array(':table' => $table, ':key' => $key));
        break;
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