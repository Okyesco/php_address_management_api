<?php
    require_once '../../includes/config.php';
    require_once '../../core/account.php';

    header("Access-Control_Allow-Origin: *");
    header("Content-Type: application/json");

    $account = new Account ($mysqli);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $account->register();
    }
?>