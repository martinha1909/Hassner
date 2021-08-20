<?php
    $_SESSION['dependencies'] = 1;
    include '../control/Dependencies.php';

    $conn = connect();
    $save_info = $_POST['save_info'];
    $transit_no = $_POST['transit_no'];
    $inst_no = $_POST['inst_no'];
    $account_no=$_POST['account_no'];
    $swift = $_POST['swift'];
    if($save_info == "Yes")
    {
        if(!empty($transit_no) && !empty($inst_no) && !empty($account_no) && !empty($swift))
        {
            $_SESSION['coins'] = round($_SESSION['coins'], 2);
            saveUseraccountInfo($conn, $_SESSION['username'], $transit_no, $inst_no, $account_no, $swift);
            $_SESSION['notify'] = sellSiliqas($conn, $_SESSION['username'], $_SESSION['coins']);
            $_SESSION['btn_show'] = 0;
            $_SESSION['cad'] = 0;
            $_SESSION['coins'] = 0;
            $_SESSION['saved'] = 0; 
            $_SESSION['siliqas'] = 0;
            $_SESSION['currency'] = 0;
        }
        else
        {
            $_SESSION['notify'] = 2; 
        }
    }
    else
    {
        if(!empty($transit_no) && !empty($inst_no) && !empty($account_no) && !empty($swift))
        {
            $_SESSION['coins'] = round($_SESSION['coins'], 2);
            $_SESSION['notify'] = sellSiliqas($conn, $_SESSION['username'], $_SESSION['coins']);
            $_SESSION['cad'] = 0;
            $_SESSION['coins'] = 0;
            $_SESSION['siliqas'] = 0;
            $_SESSION['saved'] = 0; 
            $_SESSION['currency'] = 0;
        }
        else
        {
            $_SESSION['notify'] = 2;
        }
    }
    $_SESSION['saved'] = 0;
    $_SESSION['dependencies'] = 0;

     
    
    returnToMainPage();
    
?>