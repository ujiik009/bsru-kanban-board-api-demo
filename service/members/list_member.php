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
                SELECT 
                    project_user.id as id,
                    project_user.user_id as user_id,
                    users.email as email,
                    users.full_name as full_name,
                    users.phone as phone,
                    users.position as position,
                    users.bio as bio
                    FROM `project_user` 
                INNER JOIN 
                    users on (project_user.user_id = users.id)
                WHERE 
                    project_user.project_id = '{$_GET["project_id"]}';
                ";

                $result = mysqli_query($database->getConnection(), $sql);

                if ($result) {
                    $rows = [];
                    while ($row = mysqli_fetch_assoc($result)) {
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
    $return["status"] = "Method not allow";
}

echo json_encode($return);
