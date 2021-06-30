<?php
    session_start();
    include 'logic.php';
    include 'connection.php';
    $conn = connect();
    if($_SESSION['saved'] == 0)
    {
        $save_info = $_POST['save_info'];
        if($save_info == "Yes")
        {
            // echo $_SESSION['saved'];
            $full_name = $_POST['firstname'];
            $email = $_POST['email'];
            $address=$_POST['address'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $zip = $_POST['zip'];
            $transit_no = $_POST['transit_no'];
            $inst_no = $_POST['inst_no'];
            $swift = $_POST['swift'];
            $account_no = $_POST['account_no'];
            //account_type either chequing or saving to be used later
            $account_type = $_POST['account_type'];
            if(!empty($full_name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($transit_no) && !empty($inst_no) && !empty($account_no) && !empty($account_type) && !empty($swift))
            {
                saveUserDepositInfo($conn, $_SESSION['username'], $full_name, $email, $address, $city, $state, $zip, $transit_no, $inst_no, $account_no, $swift);
                $_SESSION['notify'] = withdrawCoins($conn, $_SESSION['username'], $_SESSION['coins']);
                $_SESSION['btn_show'] = 0;
                $_SESSION['cad'] = 0;
                $_SESSION['coins'] = 0;
            }
            else
                $_SESSION['notify'] = 2; 
        }
        else
        {
            $full_name = $_POST['firstname'];
            $email = $_POST['email'];
            $address=$_POST['address'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $zip = $_POST['zip'];
            $transit_no = $_POST['transit_no'];
            $inst_no = $_POST['inst_no'];
            $account_no = $_POST['account_no'];
            $swift = $_POST['swift'];
            if(!empty($full_name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($transit_no) && !empty($inst_no) && !empty($account_no) && !empty($account_type) && !empty($swift))
            {
                $_SESSION['notify'] = withdrawCoins($conn, $_SESSION['username'], $_SESSION['coins']);
                $_SESSION['btn_show'] = 0;
                $_SESSION['cad'] = 0;
                $_SESSION['coins'] = 0;
            }
            else
                $_SESSION['notify'] = 2;
        }
    }
    else
    {
        $full_name = $_POST['firstname'];
        $email = $_POST['email'];
        $address=$_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $transit_no = $_POST['transit_no'];
        $inst_no = $_POST['inst_no'];
        $account_no = $_POST['account_no'];
        $swift = $_POST['swift'];
        if(!empty($full_name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($transit_no) && !empty($inst_no) && !empty($account_no) && !empty($account_type) && !empty($swift))
        {
            $_SESSION['notify'] = withdrawCoins($conn, $_SESSION['username'], $_SESSION['coins']);
            $_SESSION['btn_show'] = 0;
            $_SESSION['cad'] = 0;
            $_SESSION['coins'] = 0;
        }
        else
            $_SESSION['notify'] = 2;
    }
    header("Location: ../frontend/SellSiliqas.php");
    // $cad = $_SESSION['coins'] / (1+ $_SESSION['conversion_rate'])
?>