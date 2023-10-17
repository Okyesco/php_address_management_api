<?php
    require_once '../includes/config.php';
    require_once '../core/address.php';

    function post_data($mysqli){
        $address = new Address($mysqli);
        $data = json_decode(file_get_contents('php://input'));
        if ($address->createAddress($data->name, $data->gender, $data->address, $data->email, $data->contact)) {
            $existingUser = $address->getAddressByName($data->name);
            if ($existingUser){
                $currentName = $existingUser['name'];
            echo json_encode(array("message" => "Address for  '$currentName' created successfully."));
        }} else {
            echo json_encode(array("message" => "Address creation failed."));
        }
    }
?>