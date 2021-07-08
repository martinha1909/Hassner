<?php
    include '../../APIs/control/Dependencies.php';
    function getAccount($username)
    {
        $conn = connect();
        $result = searchAccount($conn, $username);
        $account = $result->fetch_assoc();
        return $account;
    }
?>