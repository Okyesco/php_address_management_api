<?php

    require_once '../includes/config.php';
    require_once '../core/address.php';
    
    function get_all_data($mysqli){
        $address = new Address($mysqli);
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $data = $address->getAddressById($id);
        } else {
            $data = $address->getAllAddresses();
        }
            echo json_encode($data);
}

?>