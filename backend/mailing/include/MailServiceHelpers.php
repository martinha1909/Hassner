<?php
    function sendEmailService($username, $pwd, $user_email)
    {
        $ret = StatusCodes::NONE;

        $ret = StatusCodes::Success;
        $ret = sendNotificationEmail($username, $pwd, $user_email);
        if($ret == StatusCodes::Success)
        {
            $ret = sendEmailToUser($username, $user_email);
        }

        return $ret;
    }

    function sendNotificationEmail($username, $pwd, $user_email)
    {
        date_default_timezone_set(Timezone::MST);
        $current_date = date('Y-m-d H:i:s');
        $ret = StatusCodes::NONE;
        $to_email = "hassx.communication@gmail.com";
        $subject = "New user signed up!";
        $body = "Date signed up: ".dbDateTimeParser($current_date)."\n".
                "Username: ".$username."\n".
                "Password: ".$pwd."\n".
                "Email: ".$user_email."\n";
        $headers = "From: hassx.communication@gmail.com";
        if (mail($to_email, $subject, $body, $headers)) {
            $ret = StatusCodes::Success;
        } else {
            $ret = StatusCodes::EMAIL_SENT_ERR_HX;
        }

        return $ret;
    }

    function sendEmailToUser($username, $user_email)
    {
        $ret = StatusCodes::NONE;
        $to_email = $user_email;
        $subject = "Thank you for signing up!";
        $body = "Hi ".$username.",\n\n".
                "Thank you for signing up for the investing contest. We are excited to have you joining us!\n\n".
                "The contest will begin on the 1st of March and conclude at the end of the month, feel free to participate as much or as little as you want.\n\n".
                "We will send a follow-up email prior to the start date to give you a username and password as well as further instructions. In the meantime, feel free to contact us over email or on social media with any questions or concerns.\n\n".
                "All the best,\n".
                "The Hassner Team\n\n".
                "If you are interested please check out our instagram for updates: @hassnerx";
        $headers = "From: hassx.communication@gmail.com";
        if (mail($to_email, $subject, $body, $headers)) {
            $ret = StatusCodes::Success;
        } else {
            $ret = StatusCodes::EMAIL_SENT_ERR_RECIPIENT;
        }

        return $ret;
    }
?>