<?php

    require_once "../core/contact_us.php";
    include_once "../includes/config.php";
    

    function get_feedback($mysqli){
        $feedback = new ContactUs($mysqli);
        $data = json_decode(file_get_contents('php://input'));

        $empty_name = $feedback->empty_field($data->name);
        $empty_email = $feedback->empty_field($data->email);
        $empty_subject = $feedback->empty_field($data->subject);
        $empty_message = $feedback->empty_field($data->message);

        $is_valid_email = $feedback->email_validate($data->email);

        if (!$empty_name && !$empty_email && !$empty_subject && !$empty_message && $is_valid_email) {
            if ($feedback->get_feedback($data->name, $data->email, $data->subject, $data->message)) {
                echo json_encode(array("message" => "Feedback received, we will revert back to you shortly."));
            } else {
                echo json_encode(array("message" => "Unable to send feedback."));
            }
        }else{
            echo json_encode(array("message" => "Invalid Input(s) !!!"));
        }
        
    }
        


?>