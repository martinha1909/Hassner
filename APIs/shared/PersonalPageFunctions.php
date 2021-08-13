<?php

    //Gets all columns in the account table in the database that matches the $user_username
    function getAccount($user_username)
    {
        $conn = connect();
        $result = searchAccount($conn, $_SESSION['username']);
        $account_info = $result->fetch_assoc();
        return $account_info;
    }

    //Prints the indicated info with only first and last characters to be visible, the other characters is replaced with a *
    function printUserImportantInfo($info)
    {
        $chars = str_split($info);
        echo '<p>';
        $i = 0;
        foreach($chars as $char)
        {
            if($i == 0 || $i == sizeof($chars)-1)
            {
                echo $char;
            }
            else if($char == '-')
            {
                echo $char;
            }
            else
            {
                echo '*';
            }
            $i++;
        }
        echo '</p>';
    }
?>