<?php
    session_start();
    include 'logic.php';
    include 'connection.php';
    $conn = connect();
    if($_SESSION['saved'] == 0)
    {
        $save_info = $_POST['save_info'];
        echo "1";
        if($save_info == "Yes")
        {
            echo "2";
            // echo $_SESSION['saved'];
            $full_name = $_POST['firstname'];
            $email = $_POST['email'];
            $address=$_POST['address'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $zip = $_POST['zip'];
            $card_name = $_POST['cardname'];
            $card_number = $_POST['cardnumber'];
            $expmonth = $_POST['expmonth'];
            $expyear = $_POST['expyear'];
            $cvv = $_POST['cvv'];
            echo $expmonth;
            if(!empty($full_name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($card_name) && !empty($card_number) && !empty($expmonth) && !empty($expyear) && !empty($cvv))
            {
                saveUserPaymentInfo($conn, $_SESSION['username'], $full_name, $email, $address, $city, $state, $zip, $card_name, $card_number);
                $_SESSION['notify'] = purchaseCoins($conn, $_SESSION['username'], $_SESSION['coins']);
                $_SESSION['btn_show'] = 0;
                $_SESSION['cad'] = 0;
                $_SESSION['coins'] = 0;
            }
            else
                $_SESSION['notify'] = 2; 
        }
        else
        {
            echo "3";
            $full_name = $_POST['firstname'];
            $email = $_POST['email'];
            $address=$_POST['address'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $zip = $_POST['zip'];
            $card_name = $_POST['cardname'];
            $card_number = $_POST['cardnumber'];
            $expmonth = $_POST['expmonth'];
            $expyear = $_POST['expyear'];
            $cvv = $_POST['cvv'];
            echo $expmonth;
            if(!empty($full_name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($card_name) && !empty($card_number) && !empty($expmonth) && !empty($expyear) && !empty($cvv))
            {
                $_SESSION['notify'] = purchaseCoins($conn, $_SESSION['username'], $_SESSION['coins']);
                $_SESSION['cad'] = 0;
                $_SESSION['coins'] = 0;
            }
            else
                $_SESSION['notify'] = 2;
        }
    }
    else
    {
        echo "4";
        $full_name = $_POST['firstname'];
            $email = $_POST['email'];
            $address=$_POST['address'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $zip = $_POST['zip'];
            $card_name = $_POST['cardname'];
            $card_number = $_POST['cardnumber'];
            $expmonth = $_POST['expmonth'];
            $expyear = $_POST['expyear'];
            $cvv = $_POST['cvv'];
            echo $expmonth;
            if(!empty($full_name) && !empty($email) && !empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($card_name) && !empty($card_number) && !empty($expmonth) && !empty($expyear) && !empty($cvv))
            {
                $_SESSION['notify'] = purchaseCoins($conn, $_SESSION['username'], $_SESSION['coins']);
                $_SESSION['cad'] = 0;
                $_SESSION['coins'] = 0;
            }
            else
                $_SESSION['notify'] = 2;
    }
    header("Location: ../frontend/CheckoutView.php");
    
?>