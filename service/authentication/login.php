<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include __DIR__ . "/../../database/database.php";
include __DIR__ . "/../../helper/helper_jwt.php";
include __DIR__ . "/../../helper/cors.php";


cors();


$return = array();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    //receive JSON POST
    $json = file_get_contents('php://input');

    try {

        if ($json != null) {
            // Converts it into a PHP object
            $body = json_decode($json, true);


            $password_encryp = md5(md5($body["password"]));

            $sql = "SELECT 
            id,
            email,
            full_name,
            phone,
            position,
            bio 
            FROM `users`
            WHERE 
                `email` = '{$body["email"]}' 
            AND
                `password` = '{$password_encryp}' ";

            $result = mysqli_query($database->getConnection(), $sql);

            if ($result) {
                if (mysqli_num_rows($result) == 1) {
                    $user_data = mysqli_fetch_assoc($result);
                    $return["status"] = true;
                    $return["data"] = [
                        "user_info" => $user_data,
                        "token" => encode_jwt($user_data)
                    ];
                } else {
                    $return["status"] = false;
                    $return["message"] = "Password is incorrect";
                }
            } else {
                $return["status"] = false;
                $return["message"] = mysqli_error($database->getConnection());
            }
        } else {
            $return["status"] = false;
            $return["message"] = "body data can not be null";
        }
    } catch (\Throwable $th) {
        $return["status"] = false;
        $return["message"] = "have something error";
    }
} else {
    $return["status"] = false;
    $return["message"] = "Method not allow";
}




echo json_encode($return);
