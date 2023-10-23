<?php

class Account {
    private $conn;

    // Store cipher method
    private $ciphering = "AES-256-CBC";
     // Use OpenSSL encryption method
    private $iv_length;
    private $options = 0; 
   
    // Use random_bytes() function to generate a random initialization vector(iv)
    private $encryption_iv = '1234567891011121';
     
     // Use php_uname() as the encryption key
     private $encryption_key;
             

    public function __construct($db) {
        $this->conn = $db;
        $this->iv_length = openssl_cipher_iv_length($this->ciphering);
        $this->encryption_key = openssl_digest(php_uname(), 'MD5', TRUE);
    }

    
    public function verify_user($email, $pwd){
        $existing_user = $this->getUserByEmail($email);
        $existing_user_email = $existing_user["email"];
        $existing_user_pwd = $existing_user["pwd"];
        // $decrypted_existing_user_pwd = $this->decryption($existing_user_pwd);
        $encrypted_pwd = $this->encryption($pwd);

        if ($email == $existing_user_email && $encrypted_pwd == $existing_user_pwd){
            return true;
        } else {
            return false;
        }

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


    public function email_validate($email){
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              return false;
            }else{
                return true;
            }
          
    }

    public function email_exists($email1, $email2){
        if ($email1 == $email2) {
            return true;
        }else{
            return false;
        }
    }

    public function empty_field($input){
        $refined_input = trim($input);
        if (empty($refined_input)) {
            return true;
        }
    }


    public function encryption($string){
        // Encryption process
        $encrypted_string = openssl_encrypt($string, $this->ciphering,
            $this->encryption_key, $this->options, $this->encryption_iv);

        return $encrypted_string;
    }


    public function decryption($encrypted_text){
        // Decryption process
        $decrypted_string = openssl_decrypt($this->encryption($encrypted_text), $this->ciphering, $this->encryption_key, $this->options, $this->encryption_iv);
        
        return $decrypted_string;
    }


    public function register(){
        $data = json_decode(file_get_contents('php://input'));
        if ($this->email_validate($data->email)){

            $existingUser = $this->getUserByEmail($data->email);

            $email_exist = $this->email_exists($data->email, $existingUser["email"]);

            $empty_name = $this->empty_field($data->name);
            $empty_pwd = $this->empty_field($data->pwd);

            $is_valid_email = $this->email_validate($data->email);

            if (!$empty_name && !$empty_pwd && $is_valid_email){
                if (!$email_exist){
                    $encrypted_pwd = $this->encryption($data->pwd);

                    if ($this->createUser($data->name, $data->email, $encrypted_pwd)) {
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

    public function login(){

        $data = json_decode(file_get_contents('php://input'));
        
        if ($this->verify_user($data->email, $data->pwd)){
            session_start();
            $existing_user = $this->getUserByEmail($data->email);
            $existing_user_id = $existing_user["id"];
            echo json_encode(array("message" => "User authenticated successfully."));
            $_SESSION['user_id'] = $existing_user_id; // Store user information in a session
            // header('Location: dashboard.php'); // Redirect to a protected page
            exit;
                        
        }else {
            echo json_encode(array("message" => "Invalid Email or password !!!"));
        }      
    }

    public function destroy_session(){
        session_start();

        // Unset all session variables
        session_unset();

        // Destroy the session
        session_destroy();
    }
    public function logout(){

        $this->destroy_session();
        echo json_encode(array("message" => "Logout successfully !!!"));

        // Redirect to the login page
        // header('Location: login.php');
        exit;
    }

    public function verify_email($email){
        $existing_user = $this->getUserByEmail($email);
        $existing_user_email = $existing_user["email"];

        if ($email == $existing_user_email){
            return true;
        } else {
            return false;
        }

    }

    public function update_pwd($pwd, $email){
        $query = "UPDATE user_data SET pwd = ? WHERE email = ?";
        $stmt = $this->conn->prepare($query);

        $encrypted_pwd = $this->encryption($pwd);
    
        // This piece of code Bind the parameter and set its value
        $stmt->bind_param("ss", $encrypted_pwd, $email);
    
        if ($stmt->execute()) {

            return true;
        } else {
            // This piece of code Handle the database query error
            echo "Error: " . $stmt->error;
            return false;
        }
    }
    public function reset_pwd($pwd, $email){
        $verified_email = $this->verify_email($email);

        if ($verified_email){
            $this->update_pwd($pwd, $email);
        
            echo json_encode(array("message" => "Password Changed Successfully."));;
        }else{
            echo json_encode(array("message" => "User doesn't exist !!!"));
        }
    }




}

?>