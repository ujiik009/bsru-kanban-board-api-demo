<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include __DIR__ . "/../../database/database.php";
include __DIR__ . "/../../helper/cors.php";
cors();
$return = array();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();

    //receive JSON POST
    $json = file_get_contents('php://input');
    // Converts it into a PHP object
    $body = json_decode($json, true);


    $password_encryp = md5(md5($body["password"]));

    $sql = "INSERT INTO users (id,email, password, full_name,phone,position,bio)
            VALUES (
                uuid(),
                '{$body["email"]}', 
                '{$password_encryp}', 
                '{$body["full_name"]}',
                '{$body["phone"]}',
                '{$body["position"]}',
                '{$body["bio"]}'
                )";

    if (mysqli_query($database->getConnection(), $sql)) {
        $return["status"] = true;
        $return["message"] = "Register Success!!!";
    } else {
        $return["status"] = false;
        $return["message"] = mysqli_error($database->getConnection());
    }
} else {
    $return["status"] = "Method not allow";
}



echo json_encode($return);
