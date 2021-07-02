<?php
    function getUserBalance($user_username)
    {
        $conn = connect();
        $result = searchAccount($conn, $user_username);
        $balance = $result->fetch_assoc();     
        return $balance['balance'];   
    }
?>