<?php

class ContactUs {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function get_feedback($name, $email, $subject, $message) {
        // This piece of code Use prepared statements to prevent SQL injection
        
        $query = "INSERT INTO contact_us (name, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
    
        // This piece of code Bind parameters and set their values
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
    
        if ($stmt->execute()) {
            return true;
        } else {
            // This piece of code Provide an error message for debugging
            echo "Error: " . $stmt->error;
            return false;
        }
    }



    public function email_validate($email){
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              return false;
            }else{
                return true;
            }
          
    }

    public function empty_field($input){
        $refined_input = trim($input);
        if (empty($refined_input)) {
            return true;
        }
    }
}

?>