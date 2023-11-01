<?php

    require_once '../includes/config.php';
    require_once '../core/address.php';
    require_once '../endpoints/get.php';
    require_once '../endpoints/post.php';
    require_once '../endpoints/delete.php';
    require_once '../endpoints/put.php';


    // header("Access-Control_Allow-Origin: *");
    header('Access-Control-Allow-Origin: http://localhost:3000');
    header('Access-Control-Allow-Headers: Content-Type'); 
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

    header("Content-Type: application/json");


    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        get_all_data($mysqli);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        post_data($mysqli);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        put_data($mysqli);
    }
    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        delete_data($mysqli);
    }


?>
