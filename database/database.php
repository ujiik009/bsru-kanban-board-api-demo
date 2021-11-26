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
