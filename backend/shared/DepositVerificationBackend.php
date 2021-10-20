<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/StatusCodes.php';
    include '../constants/LoggingModes.php';

    $_SESSION['logging_mode'] = LogModes::WITHDRAW;

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
            $_SESSION['cad'] = round($_SESSION['cad'], 2);
            saveUseraccountInfo($conn, $_SESSION['username'], $transit_no, $inst_no, $account_no, $swift);
            $_SESSION['status'] = withdraw($conn, $_SESSION['username'], $_SESSION['cad']);
            $_SESSION['btn_show'] = 0;
            $_SESSION['cad'] = 0;
            $_SESSION['cad'] = 0;
            $_SESSION['saved'] = 0; 
            $_SESSION['fiat'] = 0;
        }
        else
        {
            $_SESSION['status'] = StatusCodes::ErrEmpty; 
        }
    }
    else
    {
        if(!empty($transit_no) && !empty($inst_no) && !empty($account_no) && !empty($swift))
        {
            $_SESSION['cad'] = round($_SESSION['cad'], 2);
            $_SESSION['status'] = withdraw($conn, $_SESSION['username'], $_SESSION['cad']);
            $_SESSION['cad'] = 0;
            $_SESSION['cad'] = 0;
            $_SESSION['fiat'] = 0;
            $_SESSION['saved'] = 0;
        }
        else
        {
            $_SESSION['status'] = StatusCodes::ErrEmpty;
        }
    }
    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>