<?php

    require_once '../includes/config.php';
    require_once '../endpoints/contact_us.php';

    header("Access-Control_Allow-Origin: *");
    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        get_feedback($mysqli);
    }


?>