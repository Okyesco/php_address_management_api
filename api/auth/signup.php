<?php
    require_once '../../includes/config.php';
    require_once '../../core/account.php';

    header("Access-Control_Allow-Origin: *");
    header("Content-Type: application/json");

    function register($mysqli){
        $account = new Account ($mysqli);
        $data = json_decode(file_get_contents('php://input'));
        if ($account->email_validate($data->email)){

            $existingUser = $account->getUserByEmail($data->email);

            $email_exist = $account->email_exists($data->email, $existingUser["email"]);

            $empty_name = $account->empty_field($data->name);
            $empty_pwd = $account->empty_field($data->pwd);

            $is_valid_email = $account->email_validate($data->email);

            if (!$empty_name && !$empty_pwd && $is_valid_email){
                if (!$email_exist){
                    if ($account->createUser($data->name, $data->email, $data->pwd)) {
                        // $existingUser = $address->getUserByEmail($data->email);
                        $userName = $data->name;
                        echo json_encode(array("message" => "Account for  '$userName' created successfully."));
                        
                    }else {
                        echo json_encode(array("message" => "Failed to create the user. !!!"));
                    } 
            
            }else{
                echo json_encode(array("message" => "Email alreday exists, Please login !!!"));   
            }
            }else {
                echo json_encode(array("message" => "Invalid Inputs !!!"));
                }  
        } else {
            echo json_encode(array("message" => "Invalid Email !!!"));
            }  
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        register($mysqli);
    }
?>