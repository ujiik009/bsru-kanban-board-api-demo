<?php
    require_once __DIR__."/database/database.php";
    require_once __DIR__."/helper/helper_jwt.php";
    $database = new Database();


    var_dump(encode_jwt("apirat")) ;
?>