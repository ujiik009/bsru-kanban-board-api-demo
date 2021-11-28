# ⚙ API source code

## Preparation

1. ทำการเปิด folder project ด้วย โปรแกรม vscode โดยทำการเปิด ที่อยู่ของ xampp โดยกดที่ปุ่ม `Explorer`

![](<../.gitbook/assets/image (163).png>)

![](<../.gitbook/assets/image (21).png>)

2\. เข้าไปที่ folder htdocs

![](<../.gitbook/assets/image (128).png>)

คลิกขวาที่ folder `api_kanban_board` และทำการเลือก `Open With Code`

![](<../.gitbook/assets/image (61).png>)

## 1. Authentication

### ติดตั้ง lib php-jwt

โดย ทำการเปิด เมนู terminal > new terminal

![](<../.gitbook/assets/image (16).png>)

จากนั้นให้พิมพ์คำสั่ง `composer require firebase/php-jwt`

```
composer require firebase/php-jwt
```

![](<../.gitbook/assets/image (146).png>)

เมื่อ install เสร็จแล้ว จะปรากฎ folder ดังรูป

![](<../.gitbook/assets/image (144).png>)

### เพิ่มไฟล์ `.htaccess`

1. ทำการ เพิ่มไฟล์ `.htaccess` ไปยัง ตำแหน่ง root project&#x20;

![](<../.gitbook/assets/image (50).png>)

2\. ทำการเปิดไฟล์ .htaccess และทำการ copy code ชุดด้านล่างไปแทนที่

{% code title=".htaccess" %}
```xml
<IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "*"
</IfModule>
```
{% endcode %}

### เตรียมไฟล์ config.ini เพื่อเก็บ&#x20;

1\. ทำการ เพิ่มไฟล์ `config.ini` ที่ตำแหน่ง root project

![](<../.gitbook/assets/image (2).png>)



ให้ทำการเปิดไฟล์ config.ini และทำการ copy code ชุดด้านล่างนี้ไปแทนที่

{% code title="config.ini" %}
```ini
DB_HOST=127.0.0.1
DB_PORT=3306
DB_USER=root
DB_PASSWORD=
DB_DATABASE=kanban_project_db
KEY_JWT=bsru_key
```
{% endcode %}

### เพิ่มไฟล์ database connection

1. ทำการสร้าง folder `database` ที่ตำแหน่ง root project

![](<../.gitbook/assets/image (64).png>)

จากนั้นทำการ สร้างไฟล์ `database.php` ภายใต้ `database/`

![](<../.gitbook/assets/image (33).png>)

เมื่อทำการสร้างไฟล์ `database.php` แล้วให้ทำการเปิดไฟล์ `database.php` แล้วทำการ copy code ชุดด้านล่าง ไปแทนที่

{% code title="database.php" %}
```php
<?php

class Database
{
    private $connection = null;
    public function __construct()
    {
        // read file config
        $config =  parse_ini_file(__DIR__ . "/../config.ini");
        // Create connection
        $conn = mysqli_connect($config["DB_HOST"], $config["DB_USER"], $config["DB_PASSWORD"], $config["DB_DATABASE"], $config["DB_PORT"]);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        mysqli_set_charset($conn, "utf8");
        $this->connection = $conn;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
```
{% endcode %}

โดย code ด้านบน เป็นการ เชื่อต่อไปยัง ฐานข้อมูลที่เราทำการสร้างไว้อยู่ก่อนแล้ว อ้างอิงจาก[#create-database-schema](database-diagram.md#create-database-schema "mention")&#x20;

### เพิ่ม jwt helper

1. ทำการสร้าง Folder helper ที่ตำแหน่ง root project

![](<../.gitbook/assets/image (158).png>)

2\. ทำการสร้างไฟล์ `helper_jwt.php` ภายใต้ `helper/`

![](<../.gitbook/assets/image (96).png>)

3\. ทำการเปิดไฟล์ `helper_jwt.php` ภายใต้ `helper/` และทำการ copy code ชุดด้านล่างนี้ไปแทนที่

{% code title="helper_jwt.php" %}
```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use \Firebase\JWT\JWT;

function encode_jwt($user)
{   //กำหนด key สำหรับ encode jwt
    $config =  parse_ini_file(__DIR__ . "/../config.ini");
    $key = $config["KEY_JWT"];
    //สร้าง object ข้อมูลสำหรับทำ jwt
    $payload = array(
        "user" => $user,
        "date_time" => date("Y-m-d H:i:s") //กำหนดวันเวลาที่สร้าง
    );
    //สร้าง JWT สำหรับ object ข้อมูล
    $jwt = JWT::encode($payload, $key);
    //เพื่อความปลาดภัยยิ่งขึ้นเมื่อได้ JWT แล้วควรเข้ารหัสอีกชั้นหนึ่ง
    // return token ที่สร้าง
    return $jwt;
}

function decode_jwt($jwt)
{
    //กำหนด key สำหรับ decode jwt โดย
    $config =  parse_ini_file(__DIR__ . "/../config.ini");
    $key = $config["KEY_JWT"];
    try{
        //ถอดรหัส token
        $jwt= $jwt;
        //decode token ให้เป็นข้อมูล user
        $payload = JWT::decode($jwt, $key, array('HS256'));

    }catch(Exception $e)
    {   //กรณี Token ไม่ถูกต้องจะ return false
        return false;
    }
   
    //return ข้อมูล user กลับไป
    return  (array)$payload;

}
```
{% endcode %}

### เพิ่ม cors helper

1.ทำการสร้างไฟล์ `cors.php` ภายใต้ ้`helper/`

![](<../.gitbook/assets/image (143).png>)

2\. ทำการเปิดไฟล์ `cors.php` ภายใต้ `helper/` แล้วทำการ copy code ชุดด้านล่างนี้ไปแทนที่

{% code title="cors.php" %}
```php
<?php
function cors()
{

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET,POST,PUT,OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}

```
{% endcode %}



### 1.1 login

1. ทำการสร้าง Folder `service` ที่ตำแหน่ง root project

![](<../.gitbook/assets/image (107).png>)

2\. ทำการสร้าง Folder  `authentication` ภายใต้ `service/`

![](<../.gitbook/assets/image (115).png>)

3\. ทำการสร้าง File `login.php` ภายใต้ `service/authentication`&#x20;

![](<../.gitbook/assets/image (41).png>)

4\. ทำการเปิดไฟล์ `login.php` ภายใต้ `service/authentication` แล้วทำการ copy code ด้านล่างมาแทนที่

{% code title="login.php" %}
```php
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
h
```
{% endcode %}

### 1.2 register

1. ทำการสร้างไฟล์ **register.php ภายใต้ **`service/authentication `

![](<../.gitbook/assets/image (133).png>)

2\. ทำการเปิดไฟล์  **register.php ภายใต้ **`service/authentication` และทำการ copy code ชุดด้านล่างนี้มาแทนที่

{% code title="register.php" %}
```php
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
    $return["status"] = false;
    $return["message"] = "Method not allow";
}



echo json_encode($return);

```
{% endcode %}

Github : [https://github.com/ujiik009/bsru-kanban-board-api-demo/tree/ad8fe3379b6b540cb4dbd4634e6835b56306b0da](https://github.com/ujiik009/bsru-kanban-board-api-demo/tree/ad8fe3379b6b540cb4dbd4634e6835b56306b0da)

## 2. Projects

### 2.1 List of Project

1. ทำการสร้าง Folder `projects` ภายใต้ `service/`

![](<../.gitbook/assets/image (65).png>)

2\. ทำการสร้าง File list\_project.php ภายใต้ `service/projects`

![](<../.gitbook/assets/image (38).png>)

3.ทำการเปิด File `list_project.php` ภายใต้ `service/projects และทำการ` copy code ชุดด้านล่างนี้ไปแทนที่&#x20;

{% code title="list_project.php" %}
```php
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
                SELECT * FROM projects
                ";

                $result = mysqli_query($database->getConnection(), $sql);

                if ($result) {
                    $rows = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $sql_task_by_project = "SELECT * FROM `tasks` WHERE project_id = '{$row['id']}';";

                        $result_task = mysqli_query($database->getConnection(), $sql_task_by_project);
                        $task = [];
                        while ($task_row = mysqli_fetch_assoc($result_task)) {
                            $task[] = $task_row;
                        }
                        $row["tasks"] = $task;
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

```
{% endcode %}

### 2.2 Get Project By id

1. ทำการสร้าง File `get_project.php` ภายใต้ `service/projects`

![](<../.gitbook/assets/image (52).png>)

2\. จากนั้นทำการเปิด File `get_project.php` ภายใต้ `service/projects แล้วทำการ copy code ชุดด้านล่างนี้ไปแทนที่`

{% code title="get_project.php" %}
```php
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
                SELECT * FROM projects
                WHERE id = '{$_GET["id"]}' ;
                ";

                $result = mysqli_query($database->getConnection(), $sql);

                if ($result) {
                    $rows = [];

                    if (mysqli_num_rows($result) == 1) {
                        $return["status"] = true;
                        $return["data"] = mysqli_fetch_assoc($result);
                    } else {
                        $return["status"] = false;
                        $return["message"] = "Project not found";
                    }
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

```
{% endcode %}

### 2.3 Create project

1. ทำการสร้าง File `create_project.php` ภายใต้ `service/projects`

![](<../.gitbook/assets/image (50) (1).png>)

2\. จากนั้นทำการเปิด File `create_project.php` ภายใต้ `service/projects แล้วทำการ copy code ชุดด้านล่างนี้ไปแทนที่`

{% code title="create_project.php" %}
```php
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
                $sql = "
                INSERT INTO `projects`
                    (
                        `id`,
                        `name`,
                        `description`,
                        `start_date`,
                        `end_date`,
                        `creator`,
                        `created_at`
                    ) 
                    VALUES 
                    (
                        uuid(),
                        '{$body["name"]}',
                        '{$body["description"]}',
                        '{$body["start_date"]}',
                        '{$body["end_date"]}',
                        '{$user["user"]->id}',
                        '{$created_at}'
                    )
                ";

                $result = mysqli_query($database->getConnection(), $sql);

                if ($result) {
                    $return["status"] = true;
                    $return["message"] = "Create Project Successfully";
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

```
{% endcode %}

### 2.4 Update Project by project id

1. ทำการสร้าง File `update_project.php` ภายใต้ `service/projects`

![](<../.gitbook/assets/image (28).png>)

2\. ทำการเปิด File`update_project.php` ภายใต้ `service/projects` จากนั้นทำการ copy code ชุดด้านล่างมาแทนที่

{% code title="update_project.php" %}
```php
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

```
{% endcode %}

### 2.5 Delete Project by project id

1. ทำการสร้าง File delete`_project.php` ภายใต้ `service/projects`

![](<../.gitbook/assets/image (61) (1).png>)

2\. จากนั้นทำการเปิด File delete`_project.php` ภายใต้ `service/projects แล้วทำการ copy code ชุดด้านล่างนี้ไปแทนที่`

{% code title="delete_project.php" %}
```php
<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include __DIR__ . "/../../database/database.php";
include __DIR__ . "/../../helper/helper_jwt.php";
include __DIR__ . "/../../helper/cors.php";
cors();
$return = array();


if ($_SERVER["REQUEST_METHOD"] == "DELETE") {

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
                DELETE FROM `projects` 
                WHERE id ='{$_GET["id"]}'
                ";
                $result = mysqli_query($database->getConnection(), $sql);
                if ($result) {
                    $return["status"] = true;
                    $return["message"] = "Delete Project Successfully";
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

```
{% endcode %}

### 2.6 invite user to project

1. ทำการสร้าง File `invite_user.php` ภายใต้ `service/projects`

![](<../.gitbook/assets/image (29).png>)

&#x20;2\. ทำการเปิด File`invite_user.php` ภายใต้ `service/projects` จากนั้นทำการ copy code ชุดด้านล่างมาแทนที่

{% code title="invite_user.php" %}
```php
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
    $return["status"] = false;
    $return["message"] = "Method not allow";
}

echo json_encode($return);

```
{% endcode %}

Github : [https://github.com/ujiik009/bsru-kanban-board-api-demo/tree/e17ccaa31ab21ec435f32cd9f8f9de57e5f1003d](https://github.com/ujiik009/bsru-kanban-board-api-demo/tree/e17ccaa31ab21ec435f32cd9f8f9de57e5f1003d)

## 3. tasks

### 3.1 List of Tasks by project id

1. ทำการสร้าง Folder `tasks` ถายใต้ `service/`

![](<../.gitbook/assets/image (68).png>)

2\. ทำการสร้าง File `task_list.php` ภายใต้ `service/tasks`

![](<../.gitbook/assets/image (150).png>)

3.ทำการเปิด File`task_list.php` ภายใต้ `service/tasks`จากนั้นทำการ copy code ชุดด้านล่างมาแทนที่

{% code title="task_list.php" %}
```php
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

```
{% endcode %}

### 3.2 Create Task

1. ทำการสร้าง File `create_task.php` ภายใต้ `service/tasks`

![](<../.gitbook/assets/image (2) (1).png>)

2.ทำการเปิด File`create_task.php` ภายใต้ `service/tasks`จากนั้นทำการ copy code ชุดด้านล่างมาแทนที่

{% code title="create_task.php" %}
```php
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
    $return["status"] = false;
    $return["message"] = "Method not allow";
}

echo json_encode($return);

```
{% endcode %}

### 3.3 Update Task by task id

1. ทำการสร้าง File `update_task.php` ภายใต้ `service/tasks`

![](<../.gitbook/assets/image (102).png>)

2.ทำการเปิด File`update_task.php` ภายใต้ `service/tasks`จากนั้นทำการ copy code ชุดด้านล่างมาแทนที่

{% code title="update_task.php" %}
```php
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

                $assign_to = ($body["assign_to"] == null) ? "null" : "'{$body["assign_to"]}'";
                $sql = "
                UPDATE `tasks`
                SET 
                    `name`='{$body["name"]}',
                    `description`='{$body["description"]}',
                    `state`='{$body["state"]}',
                    `color`='{$body["color"]}',
                    `due_date`= '{$body["due_date"]}',
                    `assign_to`= {$assign_to}
            
                WHERE id ='{$body["id"]}'
                ";
                $result = mysqli_query($database->getConnection(), $sql);
                if ($result) {
                    $return["status"] = true;
                    $return["message"] = "Edit Task Successfully";
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

```
{% endcode %}

### 3.4 delete task by task id

1. ทำการสร้าง File `delete_task.php` ภายใต้ `service/tasks`

![](<../.gitbook/assets/image (112).png>)

2.ทำการเปิด File`delete_task.php` ภายใต้ `service/tasks`จากนั้นทำการ copy code ชุดด้านล่างมาแทนที่

{% code title="delete_task.php" %}
```php
<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include __DIR__ . "/../../database/database.php";
include __DIR__ . "/../../helper/helper_jwt.php";
include __DIR__ . "/../../helper/cors.php";
cors();
$return = array();


if ($_SERVER["REQUEST_METHOD"] == "DELETE") {

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
                DELETE FROM `tasks` 
                WHERE id ='{$_GET["id"]}'
                ";
                $result = mysqli_query($database->getConnection(), $sql);
                if ($result) {
                    $return["status"] = true;
                    $return["message"] = "Delete Task Successfully";
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

```
{% endcode %}

### 4.5 Get Task In Progress

1. ทำการสร้าง ไฟล์ `task_in_progress.php` ภาย ใต้ s`ervice/task`

![](<../.gitbook/assets/image (130).png>)

2\. ทำการเปิด File`task_in_progress.php` ภายใต้ `service/tasks`จากนั้นทำการ copy code ชุดด้านล่างมาแทนที่

{% code title="task_in_progress.php" %}
```php
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
            if ($user = decode_jwt($token)) {

                $database = new Database();
                //receive JSON POST
                $json = file_get_contents('php://input');
                // Converts it into a PHP object
                $body = json_decode($json, true);

                $sql = "
                SELECT * FROM tasks
                WHERE 
                    assign_to = '{$user["user"]->id}'
                    AND
                    state = 'in_progress'
                ;
                ";

                $result = mysqli_query($database->getConnection(), $sql);

                if ($result) {
                    $rows = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $sql_assign_to = "SELECT id,email,full_name,phone,position,bio FROM `users` WHERE id = '{$row['assign_to']}';";

                        $result_assign_to = mysqli_query($database->getConnection(), $sql_assign_to);
                        $row["assign_to"] = mysqli_fetch_assoc($result_assign_to);
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

```
{% endcode %}

Github : [https://github.com/ujiik009/bsru-kanban-board-api-demo/tree/b08383c93cdd07f7175669ad15c83b8429ec3e16](https://github.com/ujiik009/bsru-kanban-board-api-demo/tree/b08383c93cdd07f7175669ad15c83b8429ec3e16)

## 4.Member

### 4.1 List of Member by project

1. ทำการ สร้าง folder member ภายใน service

![](<../.gitbook/assets/image (116).png>)

2.ทำการสร้าง File `list_member.php` ภายใต้ `service/members`

![](<../.gitbook/assets/image (131).png>)

3.ทำการเปิด File`list_member.php` ภายใต้ `service/members`จากนั้นทำการ copy code ชุดด้านล่างมาแทนที่

{% code title="list_member.php" %}
```php
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
    $return["status"] = false;
    $return["message"] = "Method not allow";
}

echo json_encode($return);

```
{% endcode %}

### 4.2 Remove Member

1. ทำการสร้าง File `remove_member.php` ภายใต้ `service/members`

![](<../.gitbook/assets/image (13).png>)

2\. ทำการเปิด File`remove_member.php` ภายใต้ `service/members`จากนั้นทำการ copy code ชุดด้านล่างมาแทนที่

{% code title="remove_member.php" %}
```php
<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include __DIR__ . "/../../database/database.php";
include __DIR__ . "/../../helper/helper_jwt.php";
include __DIR__ . "/../../helper/cors.php";
cors();
$return = array();


if ($_SERVER["REQUEST_METHOD"] == "DELETE") {

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
                DELETE FROM `project_user` 
                WHERE id ='{$_GET["id"]}'
                ";
                $result = mysqli_query($database->getConnection(), $sql);
                if ($result) {
                    $return["status"] = true;
                    $return["message"] = "Remove Member Successfully";
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

```
{% endcode %}

Github : [https://github.com/ujiik009/bsru-kanban-board-api-demo/tree/d5277e4e0a7275e02f15efff4ee385a8bdd8be8e](https://github.com/ujiik009/bsru-kanban-board-api-demo/tree/d5277e4e0a7275e02f15efff4ee385a8bdd8be8e)

## 5.Comments

### 5.1 create comment by task id

1. ทำการสร้าง Folder `comments`ถายใต้ `service/`

![](<../.gitbook/assets/image (151).png>)

2\. ทำการสร้าง File `create_comment.php` ภายใต้ `service/comments`

![](<../.gitbook/assets/image (21) (1).png>)

3\. ทำการเปิด File `create_comment.php` ภายใต้ `service/comments`จากนั้นทำการ copy code ชุดด้านล่างมาแทนที่

{% code title="create_comment.php" %}
```php
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

        
                $sql = "
                INSERT INTO `comments`
                    (
                        `id`,
                        `comment`,
                        `task_id`,
                        `commentator`
                       
                    ) 
                    VALUES 
                    (
                        uuid(),
                        '{$body["comment"]}',
                        '{$_GET["task_id"]}',
                        '{$user["user"]->id}'
                    )
                ";

                $result = mysqli_query($database->getConnection(), $sql);

                if ($result) {
                    $return["status"] = true;
                    $return["message"] = "Create Comment Successfully";
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

```
{% endcode %}

### 5.2 delete comment by comment id (your comment only)

1. ทำการสร้าง File `remove_comment.php` ภายใต้ `service/comments`

![](<../.gitbook/assets/image (162).png>)

2\. ทำการเปิด File `remove_comment.php` ภายใต้ `service/comments`จากนั้นทำการ copy code ชุดด้านล่างมาแทนที่

{% code title="remove_comment.php" %}
```php
<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include __DIR__ . "/../../database/database.php";
include __DIR__ . "/../../helper/helper_jwt.php";
include __DIR__ . "/../../helper/cors.php";
cors();
$return = array();


if ($_SERVER["REQUEST_METHOD"] == "DELETE") {

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
                DELETE FROM `comments` 
                WHERE id ='{$_GET["id"]}'
                ";
                $result = mysqli_query($database->getConnection(), $sql);
                if ($result) {
                    $return["status"] = true;
                    $return["message"] = "Delete Comment Successfully";
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

```
{% endcode %}

### 5.3 List Comment By task id

1. ทำการสร้าง File `list_comment.php` ภายใต้ `service/comments`

![](<../.gitbook/assets/image (84).png>)

2\. ทำการเปิด File `list_comment.php` ภายใต้ `service/comments`จากนั้นทำการ copy code ชุดด้านล่างมาแทนที่

{% code title="list_comment.php" %}
```php
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
                SELECT * FROM comments
                WHERE task_id = '{$_GET["task_id"]}';
                ";

                $result = mysqli_query($database->getConnection(), $sql);

                if ($result) {
                    $rows = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $sql_commentator = "SELECT id,email,full_name,phone,position,bio FROM `users` WHERE id = '{$row['commentator']}';";

                        $result_commentator = mysqli_query($database->getConnection(), $sql_commentator);
                        $row["commentator"] = mysqli_fetch_assoc($result_commentator);
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

```
{% endcode %}

Github : [https://github.com/ujiik009/bsru-kanban-board-api-demo/tree/39fcb2a3df29d7a93b660cedd42082b7463af20d](https://github.com/ujiik009/bsru-kanban-board-api-demo/tree/39fcb2a3df29d7a93b660cedd42082b7463af20d)

## 6. Profile

### 6.1 update profile

1. ทำการสร้าง Folder `profile`ถายใต้ `service/`

![](<../.gitbook/assets/image (152).png>)

2\. ทำการสร้าง File `update_profile.php` ภายใต้ `service/profile`

![](<../.gitbook/assets/image (1).png>)

3\. ทำการเปิด File `update_profile.php` ภายใต้ `service/profile`จากนั้นทำการ copy code ชุดด้านล่างมาแทนที่

{% code title="" %}
```php
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
            if ($user = decode_jwt($token)) {

                $database = new Database();
                //receive JSON POST
                $json = file_get_contents('php://input');
                // Converts it into a PHP object
                $body = json_decode($json, true);

                $sql = "
                UPDATE `users`
                SET 
                    `email`='{$body["email"]}',
                    `full_name`='{$body["full_name"]}',
                    `phone`='{$body["phone"]}',
                    `position`= '{$body["position"]}',
                    `bio`= '{$body["bio"]}'
            
                WHERE id ='{$user["user"]->id}'
                ";
                $result = mysqli_query($database->getConnection(), $sql);
                if ($result) {
                    $return["status"] = true;
                    $return["message"] = "Edit Profile Successfully";
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

```
{% endcode %}

### 6.2 change password

1. ทำการสร้าง File `change_password.php` ภายใต้ `service/profile`

![](<../.gitbook/assets/image (42).png>)

2\. ทำการเปิด File `change_password.php` ภายใต้ `service/profile `จากนั้นทำการ copy code ชุดด้านล่างมาแทนที่

{% code title="change_password.php" %}
```php
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
            if ($user = decode_jwt($token)) {
                
                $database = new Database();
                //receive JSON POST
                $json = file_get_contents('php://input');
                // Converts it into a PHP object
                $body = json_decode($json, true);

                $password_encryp = md5(md5($body["new_password"]));

                $sql = "
                UPDATE `users`
                SET 
                    `password`='{$password_encryp}'
                
                WHERE id ='{$user["user"]->id}';
                ";
                $result = mysqli_query($database->getConnection(), $sql);
                if ($result) {
                    $return["status"] = true;
                    $return["message"] = "Update Password Successfully";
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

```
{% endcode %}

Github : [https://github.com/ujiik009/bsru-kanban-board-api-demo/tree/79f8e254eb7f46265a30d9ed2f726856075f93e3](https://github.com/ujiik009/bsru-kanban-board-api-demo/tree/79f8e254eb7f46265a30d9ed2f726856075f93e3)
