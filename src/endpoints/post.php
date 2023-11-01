<?php
    require_once '../includes/config.php';
    require_once '../core/address.php';



    function post_data($mysqli){
        $address = new Address($mysqli);
        $data = json_decode(file_get_contents('php://input'));
        
        $empty_name = $address->empty_field($data->name);
        $empty_gender = $address->empty_field($data->gender);
        $empty_address = $address->empty_field($data->address);
        $empty_email = $address->empty_field($data->email);
        $empty_contact = $address->empty_field($data->contact);

        $is_valid_email = $address->email_validate($data->email);

        if (!$empty_name && !$empty_gender && !$empty_address && !$empty_email && !$empty_contact && $is_valid_email) {
            if ($address->createAddress($data->name, $data->gender, $data->address, $data->email, $data->contact)) {
                $userName = $address->getAddressByName($data->name);
                $currentName = $userName['name'];
                echo json_encode(array("message" => "Address for  '$currentName' created successfully."));
            } else {
                echo json_encode(array("message" => "Address creation failed."));
            }
        }else{
            echo json_encode(array("message" => "Invalid Input(s) !!!"));
        }
        
    }
?>