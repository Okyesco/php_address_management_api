<?php
    require_once '../../includes/config.php';
    require_once '../../core/account.php';

    header("Access-Control_Allow-Origin: *");
    header("Content-Type: application/json");

    $user = new Account($mysqli);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = json_decode(file_get_contents('php://input'));

        $pwd = $data->pwd;
        $email = $data->email;

        $valid_email = $user->email_validate($data->email);
        $empty_pwd = $user->empty_field($data->pwd);

        if ($valid_email ) {
            if (!$empty_pwd){
                $user->reset_pwd($pwd, $email);
            }else{
                echo json_encode(array('message'=> 'Invalid Input(s)'));
            }
        }else{
            echo json_encode(array("message"=> "Account with email doesn't exist !!!"));
        }

    }
?>