<?php
    function getUserBalance($conn, $username)
    {
        $ret = 0;

        $result = searchAccount($conn, $username);
        $balance = $result->fetch_assoc();
        $ret = $balance['balance'];   

        return $ret;
    }
?>