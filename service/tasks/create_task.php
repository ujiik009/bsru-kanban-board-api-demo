<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include __DIR__ . "/../../database/database.php";
include __DIR__ . "/../../helper/helper_jwt.php";
include __DIR__ . "/../../helper/cors.php";
cors();
$return = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $headers = apache_request_headers();

    if (isset($headers['authorization'])) {
        $token = $headers['authorization'];
        try {
            if ($user = decode_jwt($token)) {

                $database = new Database();
                //receive JSON POST
                $json = file_get_contents('php://input');
                // Converts it into a PHP object
                $body = json_decode($json, true);
                $created_at = date("Y-m-d H:i:s");

                $assign_to = ($body["assign_to"] == null) ? "null" : "'{$body["assign_to"]}'";
                $sql = "
                INSERT INTO `tasks`
                    (
                        `id`,
                        `project_id`,
                        `name`,
                        `description`,
                        `state`,
                        `assign_to`,
                        `color`,
                        `due_date`
                    ) 
                    VALUES 
                    (
                        uuid(),
                        '{$_GET["project_id"]}',
                        '{$body["name"]}',
                        '{$body["description"]}',
                        '{$body["state"]}',
                        {$assign_to},
                        '{$body["color"]}',
                        '{$body["due_date"]}'
                    )
                ";

                $result = mysqli_query($database->getConnection(), $sql);

                if ($result) {
                    $return["status"] = true;
                    $return["message"] = "Create Task Successfully";
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
    $return["status"] = "Method not allow";
}

echo json_encode($return);
