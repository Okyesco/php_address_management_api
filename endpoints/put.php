<?php
    require_once '../includes/config.php';
    require_once '../core/address.php';

    function put_data($mysqli){
        $address = new Address($mysqli);
        $data = json_decode(file_get_contents('php://input'));

        $existingUser = $address->getAddressById($data->id);

        // This piece of code Check if the address with the provided id exists
        if ($address->addressExists($data->id)) {
            if ($existingUser){
                $currentName = $existingUser['name'];
                if ($address->updateAddress($data->id, $data->name, $data->gender, $data->address, $data->email, $data->contact)) {
                    echo json_encode(array("message" => "Address for '$currentName' updated successfully."));
                } else {
                    echo json_encode(array("message" => "Address update failed."));
            }}
            } else {
                echo json_encode(array("message" => "Address with ID " . $data->id . " not found."));
        }
    }
?>