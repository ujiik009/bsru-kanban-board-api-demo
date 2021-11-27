<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include __DIR__ . "/../../database/database.php";
include __DIR__ . "/../../helper/helper_jwt.php";
include __DIR__ . "/../../helper/cors.php";
cors();
$return = array();


if ($_SERVER["REQUEST_METHOD"] == "GET") {

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
                SELECT * FROM tasks
                WHERE project_id = '{$_GET["project_id"]}';
                ";

                $result = mysqli_query($database->getConnection(), $sql);

                if ($result) {
                    $rows = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $sql_assign_to = "SELECT id,email,full_name,phone,position,bio FROM `users` WHERE id = '{$row['assign_to']}';";

                        $result_assign_to = mysqli_query($database->getConnection(), $sql_assign_to);
                        $row["user_assign"] = mysqli_fetch_assoc($result_assign_to);
                        $rows[] = $row;
                    }
                    $return["status"] = true;
                    $return["data"] = $rows;
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
            $return["message"] = "have something error";
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
