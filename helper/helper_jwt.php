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