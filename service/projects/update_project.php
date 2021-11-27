<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include __DIR__ . "/../../database/database.php";
include __DIR__ . "/../../helper/helper_jwt.php";
include __DIR__ . "/../../helper/cors.php";

cors();

$return = array();


if ($_SERVER["REQUEST_METHOD"] == "PUT") {

    $headers = apache_request_headers();
    if (isset($headers['authorization'])) {
        $token = $headers['authorization'];
        try {
            if (decode_jwt($token)) {

                $database = new Database();
                //receive JSON POST
                $json = file_get_contents('php://input');
                // Converts it into a PHP object
                $body = json_decode($json, true);

                $sql = "
                UPDATE `projects`
                SET 
                    `name`='{$body["name"]}',
                    `description`='{$body["description"]}',
                    `start_date`='{$body["start_date"]}',
                    `end_date`= '{$body["end_date"]}'
            
                WHERE id ='{$body["id"]}'
                ";
                $result = mysqli_query($database->getConnection(), $sql);
                if ($result) {
                    $return["status"] = true;
                    $return["message"] = "Edit Project Successfully";
                } else {
                    $return["status"] = false;
                    $return["message"] = mysqli_error($database->getConnection());
                }
            } else {
                $return["status"] = false;
                $return["message"] = "Token incorrect";
            }
        } catch (Throwable $err) {
            $return["status"] = false;
            $return["message"] = "Token incorrect";
        }
    } else {
        $return["status"] = false;
        $return["message"] = "Token Not Found";
    }
} else {
    $return["status"] = false;
    $return["message"] = "Method not allow";
}

echo json_encode($return);
