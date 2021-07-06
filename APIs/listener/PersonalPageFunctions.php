<?php
    function getAccount($user_username)
    {
        $conn = connect();
        $result = searchAccount($conn, $_SESSION['username']);
        $account_info = $result->fetch_assoc();
        return $account_info;
    }

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