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

                // query employee by array email

                $sql_user_by_email = "
                SELECT id FROM `users` 
                    WHERE email in ('" . implode("','", $body["email_array"]) . "');
                ";

                $result_user_exist = mysqli_query($database->getConnection(), $sql_user_by_email);

                $user_exist_id = [];
                while ($row_user = mysqli_fetch_assoc($result_user_exist)) {
                    $user_exist_id[] = $row_user["id"];
                }


                // generate insert mutiple record only value
                $value_sql_project_user  =  array_map(function ($user_id) use ($body) {
                    return "
                    (
                        uuid(),
                        '{$body["id"]}',
                        '{$user_id}'
                    )
                    ";
                }, $user_exist_id);

                // generate insert mutiple record full command
                $sql_invite_user = "
                INSERT INTO `project_user`
                    (
                        `id`,
                        `project_id`,
                        `user_id`
                    ) 
                    VALUES 
                    ".implode(",",$value_sql_project_user).";
                ";

                $result = mysqli_query($database->getConnection(), $sql_invite_user);

                if ($result) {
                    $return["status"] = true;
                    $return["message"] = "Invite users Successfully";
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
