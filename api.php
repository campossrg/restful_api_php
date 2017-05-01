<?php

    // RECEIVE INFORMATION
    $method = $_SERVER['REQUEST_METHOD'];                               // METHOD
    $request = explode('/', trim($_SERVER['REQUEST_URI'], '/') );       // URI INFO
    array_shift($request);
    $input = json_decode(file_get_contents('php://input'), true);       // POST DATA

    // DATABASE CONNECTION
    $host = "localhost";
    $dbname = "db_rest";
    $user = "root";
    $pass = "admin";
    $link = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

    // RETRIEVE THE TABLE AND KEY FROM URI
    $table = array_shift($request);
    if($table === 'users'){
        $key = array_shift($request);
    } else {
        // Deny resources different to 'users'
        echo "Resource must be 'users'";
        header('HTTP/1.1 404 Not Found');
        exit;
    }

    // CREATE SQL SENTENCES FOR EACH METHOD
    switch ($method) {
    case 'GET':
        $sql = "select * from $table".($key?" WHERE users_name='$key'":'');   //------>>>WRONG OPTION
        $result = $link->query($sql);                                 // this is testing app
        break;                                                        // You MUST avoid the SQL injection hack
    case 'PUT':
        $sql = $link->prepare("update $table set users_name = :set where users_name=:key");
        $result = $sql->execute(array(':set' => $input, ':key' => $key));
        break;
    case 'POST':
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

    // PRINT RESULTS
    if (!$result) {
        echo "\nIt didn't worked!!\n";
        http_response_code(404);
        $link = null;
        exit;
    }

    if ($method == 'GET') {
        foreach ($result as $row) {
            echo $row['users_name'] . "\n";
        }
    } elseif ($method == 'POST') {
        echo $input . " inserted succesfully";
    } else {
        echo $input . " modified succesfully";
    }

    $link = null; //close conn
?>
