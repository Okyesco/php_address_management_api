<?php

class Address {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }


    public function getAllAddresses() {
        $query = "SELECT * FROM addresses";
        $result = $this->conn->query($query);
    
        if ($result === false) {
            // This piece of code Handle the database query error
            echo "Error: " . $this->conn->error;
            return false;
        }
    
        $addresses = [];
    
        while ($row = $result->fetch_assoc()) {
            $addresses[] = $row;
        }
    
        return $addresses;
    }
    
    public function getAddressById($id) {
        $id = (int)$id;  // This piece of code Cast the ID to an integer to prevent SQL injection
    
        // This piece of code Use a prepared statement to prevent SQL injection
        $query = "SELECT * FROM addresses WHERE id = ?";
        $stmt = $this->conn->prepare($query);
    
        // This piece of code Bind the parameter and set its value
        $stmt->bind_param("i", $id);
    
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $address = $result->fetch_assoc();
    
            if ($address === null) {
                // This piece of code Handle the case where no address with the provided ID is found
                return array("message" => "Address not found.");
            }
    
            return $address;
        } else {
            // This piece of code Handle the database query error
            echo "Error: " . $stmt->error;
            return false;
        }
    }
    
    public function createAddress($name, $gender, $address, $email, $contact) {
        // This piece of code Use prepared statements to prevent SQL injection
        $query = "INSERT INTO addresses (name, gender, address, email, contact) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
    
        // This piece of code Bind parameters and set their values
        $stmt->bind_param("sssss", $name, $gender, $address, $email, $contact);
    
        if ($stmt->execute()) {
            return true;
        } else {
            // This piece of code Provide an error message for debugging
            echo "Error: " . $stmt->error;
            return false;
        }
    }
    

    public function updateAddress($id, $name, $gender, $address, $email, $contact) {
        // This piece of code Use a prepared statement to prevent SQL injection
        $query = "UPDATE addresses SET name = ?, gender = ?, address = ?, email = ?, contact = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
    
        // This piece of code Bind parameters and set their values
        $stmt->bind_param("sssssi", $name, $gender, $address, $email, $contact, $id);
    
        if ($stmt->execute()) {
            return true;
        } else {
            // This piece of code Provide an error message for debugging
            echo "Error: " . $stmt->error;
            return false;
        }
    }
    


    public function deleteAddress($id) {
        // This piece of code Use a prepared statement to prevent SQL injection
        $query = "DELETE FROM addresses WHERE id = ?";
        $stmt = $this->conn->prepare($query);
    
        // This piece of code Bind the parameter and set its value
        $stmt->bind_param("i", $id);
    
        if ($stmt->execute()) {
            return true;
        } else {
            // This piece of code Provide an error message for debugging
            echo "Error: " . $stmt->error;
            return false;
        }
    }

    public function addressExists($id) {
        $id = (int)$id;  // This casts the ID to an integer
        $query = "SELECT COUNT(*) as count FROM addresses WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    
        return $row['count'] > 0;
    }
    


    public function getAddressByName($name) {
     
        // This piece of code Use a prepared statement to prevent SQL injection
        $query = "SELECT * FROM addresses WHERE name = ?";
        $stmt = $this->conn->prepare($query);
    
        // This piece of code Bind the parameter and set its value
        $stmt->bind_param("s", $name);
    
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $address = $result->fetch_assoc();
    
            if ($address === null) {
                // This piece of code Handle the case where no address with the provided ID is found
                return array("message" => "Address not found.");
            }
    
            return $address;
        } else {
            // This piece of code Handle the database query error
            echo "Error: " . $stmt->error;
            return false;
        }
    }


    public function createUser($name, $email, $password) {
        // This piece of code Use prepared statements to prevent SQL injection
        
        $query = "INSERT INTO user_data (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
    
        // This piece of code Bind parameters and set their values
        $stmt->bind_param("sss", $name, $email, $password);
    
        if ($stmt->execute()) {
            return true;
        } else {
            // This piece of code Provide an error message for debugging
            echo "Error: " . $stmt->error;
            return false;
        }
    }

    public function getUserByEmail($email){
             
        // This piece of code Use a prepared statement to prevent SQL injection
        $query = "SELECT * FROM user_data WHERE email = ?";
        $stmt = $this->conn->prepare($query);
    
        // This piece of code Bind the parameter and set its value
        $stmt->bind_param("s", $email);
    
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $address = $result->fetch_assoc();
    
            if ($address === null) {
                // This piece of code Handle the case where no address with the provided ID is found
                return array("message" => "Address not found.");
            }
    
            return $address;
        } else {
            // This piece of code Handle the database query error
            echo "Error: " . $stmt->error;
            return false;
        }
    }

}

?>