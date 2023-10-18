<?php

class Account {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createUser($name, $email, $pwd) {
        // This piece of code Use prepared statements to prevent SQL injection
        
        $query = "INSERT INTO user_data (name, email, pwd) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
    
        // This piece of code Bind parameters and set their values
        $stmt->bind_param("sss", $name, $email, $pwd);
    
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
            $account = $result->fetch_assoc();
    
            if ($account === null) {
                // This piece of code Handle the case where no address with the provided ID is found
                return array("message" => "Address not found.");
            }
    
            return $account;
        } else {
            // This piece of code Handle the database query error
            echo "Error: " . $stmt->error;
            return false;
        }
    }


    function email_validate($email){
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              return false;
            }else{
                return true;
            }
          
    }


}

?>