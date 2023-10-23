<?php
    require_once '../includes/config.php';
    require_once '../core/address.php';

    function delete_data($mysqli){
        $address = new Address($mysqli);
        
        $data = json_decode(file_get_contents('php://input'));
        if (isset($data->id)) {
            $id = $data->id;
            $existingUser = $address->getAddressById($data->id);
            if ($address->addressExists($data->id)) {
                if ($existingUser){
                    $currentName = $existingUser['name'];
                    if ($address->deleteAddress($id)) {
                        echo json_encode(array("message" => "Address for '$currentName' deleted successfully."));
                    } else {
                        echo json_encode(array("message" => "Address deletion failed."));
            
            }
        }} else {
            echo json_encode(array("message" => "Missing 'id' parameter."));
        }}
    }
?>