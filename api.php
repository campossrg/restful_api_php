<?php

    $method = $_SERVER['REQUEST_METHOD'];                               // METHOD
    $request = explode('/', trim($_SERVER['REQUEST_URI'], '/') );       // URL INFO
    array_shift($request);
    $input = json_decode(file_get_contents('php://input'), true);       // POST DATA

    //DATABASE CONNECTION
    $host = "localhost";
    $dbname = "db_rest";
    $user = "root";
    $pass = "admin";
    $link = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

    // retrieve the table and key from the path
    $table = array_shift($request);
    if($table === 'users'){
        $key = array_shift($request);
        // if(empty($key)){
        //     echo "\nKEY: empty\n";
        // }
        // else{
        //     echo "\nKEY: " . $key . "\n";
        // }
    } else {
        // Deny resources different to 'users'
        echo "Resource must be 'users'";
        header('HTTP/1.1 404 Not Found');
        exit;
    }

    // return an array with all the input´s values
    // $values = array_map(function ($value) {
    //     if ($value===null) return null;
    //     // if ($value['value']===null) return null;
    //     echo "\n" . $value . "<---\n";
    //     return $value;
    // },$input);

    // build the SET part of the SQL command
    // $set = $values[0];
    // for ($i=0;$i<count($columns);$i++) {
    //     $set.=($i>0?',':'').'`'.$columns[$i].'`=';
    //     $set.=($values[$i]===null?'NULL':'"'.$values[$i].'"');
    // }

    // create SQL based on HTTP method
    switch ($method) {
    case 'GET':
        $sql = "select * from $table".($key?" WHERE users_name='$key'":'');   //----------->>>WRONG OPTION
        $result = $link->query($sql);                                 // You must avoid the SQL injection hack
        break;
    case 'PUT':
        // $sentence = $link->prepare("update :table set :set where id= :key");
        // $result = $sentence->execute(array(':table' => $table, ':set' => $set, ':key' => $key));
        // break;
    case 'POST':
        echo $input;
        $sql = "select * from $table where users_name ='$input'";
        $result = $link->query($sql);
        if($result->rowCount() > 0){
            echo "Sorry, there is already a user with this name";
            exit;
        }
        else{
            $sql = $link->prepare("insert into $table (users_name) values (:set)");
            $result = $sql->execute(array('set' => $input));
        }
        break;
    case 'DELETE':
        $sql = $link->prepare("delete from $table where users_name=:set");
        $result = $sql->execute(array(':set' => $input));
        break;
    }

    // die if SQL statement failed
    if (!$result) {
        echo "\nIt didn't worked!!\n";
        http_response_code(404);
        $link = null;
        exit;
    }

    // PRINT RESULTS
    if ($method == 'GET') {
        foreach ($result as $row) {
            echo $row['users_name'] . "\n";
        }
    } elseif ($method == 'POST') {
        echo $input . " inserted succesfully";
    } else {
        echo $input . " deleted succesfully";
    }

    $link = null; //close conn
?>
