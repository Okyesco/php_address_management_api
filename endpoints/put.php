<?php
    require_once '../includes/config.php';
    require_once '../core/address.php';

    function put_data($mysqli){
        $address = new Address($mysqli);
        $data = json_decode(file_get_contents('php://input'));

        $existingUser = $address->getAddressById($data->id);

        $empty_name = $address->empty_field($data->name);
        $empty_gender = $address->empty_field($data->gender);
        $empty_address = $address->empty_field($data->address);
        $empty_email = $address->empty_field($data->email);
        $empty_contact = $address->empty_field($data->contact);

        $is_valid_email = $address->email_validate($data->email);

        // This piece of code Check if the address with the provided id exists
        if ($address->addressExists($data->id)) {
                $currentName = $existingUser['name'];

                if (!$empty_name && !$empty_gender && !$empty_address && !$empty_email && !$empty_contact && $is_valid_email){
                    if ($address->updateAddress($data->id, $data->name, $data->gender, $data->address, $data->email, $data->contact)) {
                        echo json_encode(array("message" => "Address for '$currentName' updated successfully."));
                    } else {
                        echo json_encode(array("message" => "Address update failed."));
                }}else{
                    echo json_encode(array("message" => "Invalid Input(s) !!!"));
                }
                
        } else {
            echo json_encode(array("message" => "Address with ID " . $data->id . " not  found."));
        }
    }
?>