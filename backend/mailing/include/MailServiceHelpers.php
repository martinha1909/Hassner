<?php

use PHPMailer\PHPMailer\PHPMailer;

/**
    * Sends a notification email to us, if succeeds send a notification email to the user
    *
    * @param  	username            user sign up username
    *   
    * @param  	pwd                 user sign up password
    *
    * @param  	user_email	        user sign up email
    *
    * @return 	ret	                overall status of sending both notification and user email
    */
    function sendEmailService($username, $pwd, $user_email)
    {
        $ret = StatusCodes::NONE;

        $ret = sendNotificationEmail($username, $pwd, $user_email);
        if($ret == StatusCodes::Success)
        {
            $ret = sendEmailToUser($username, $user_email);
        }

        return $ret;
    }

    /**
    * Sends a notification email to us
    *
    * @param  	username            user sign up username
    *   
    * @param  	pwd                 user sign up password
    *
    * @param  	user_email	        user sign up email
    *
    * @return 	ret	                status of sending notification email
    */
    function sendNotificationEmail($username, $pwd, $user_email)
    {
        date_default_timezone_set(Timezone::MST);
        $current_date = date('Y-m-d H:i:s');
        $ret = StatusCodes::NONE;
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'hassx.communication@gmail.com';                     //SMTP username
            $mail->Password   = 'KaMaVi!3460';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('hassx.communication@gmail.com', 'The Hassner Team');
            $mail->addAddress('hassx.communication@gmail.com', 'The Hassner Team');     //Add a recipient

            //Content
            $mail->Subject = 'New user signed up!';
            $mail->Body = "Date signed up: ".dbDateTimeParser($current_date)."\n".
                          "Username: ".$username."\n".
                          "Password: ".$pwd."\n".
                          "Email: ".$user_email."\n";

            $mail->send();
            $ret = StatusCodes::Success;
        }catch (Exception $e) {
            $ret = StatusCodes::EMAIL_SENT_ERR_HX;
        }

        return $ret;
    }

    /**
    * Sends a notification email to the user who has signed up
    *
    * @param  	username            user sign up username
    *
    * @param  	user_email	        user sign up email
    *
    * @return 	ret	                status of sending notification email
    */
    function sendEmailToUser($username, $user_email)
    {
        echo $user_email;
        date_default_timezone_set(Timezone::MST);
        $ret = StatusCodes::NONE;
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'hassx.communication@gmail.com';                     //SMTP username
            $mail->Password   = 'KaMaVi!3460';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('hassx.communication@gmail.com', 'The Hassner Team');
            $mail->addAddress($user_email, $username);     //Add a recipient

            //Content
            $mail->Subject = 'Thank you for signing up!';
            $mail->Body = "Hi ".$username.",\n\n".
                          "Thank you for signing up for the investing contest. We are excited to have you joining us!\n\n".
                          "The contest will begin on the 1st of March and conclude at the end of the month, feel free to participate as much or as little as you want.\n\n".
                          "We will send a follow-up email prior to the start date to give you a username and password as well as further instructions. In the meantime, feel free to contact us over email or on social media with any questions or concerns.\n\n".
                          "All the best,\n".
                          "The Hassner Team\n\n".
                          "If you are interested please check out our instagram for updates: @hassnerx";;

            $mail->send();
            $ret = StatusCodes::Success;
        }catch (Exception $e) {
            $ret = StatusCodes::EMAIL_SENT_ERR_HX;
        }
        return $ret;
    }
?>