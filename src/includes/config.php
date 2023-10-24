<?php
    // #Docker
    $database_host = "db";
    $database_username = "root";
    $database_password = "root";
    $database_name = "address_management_app";

    #LOCALHOST
    // $database_host = "127.0.0.1";
    // $database_username = "root";
    // $database_password = "";
    // $database_name = "address_management_app";

    
    $mysqli = new mysqli($database_host, $database_username, $database_password, $database_name);
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    
    // This piece of code Set the charset to UTF-8 (if needed)
    if (!$mysqli->set_charset("utf8")) {
        die("Error loading character set utf8: " . $mysqli->error);
    }

    

?>