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
    $msg = "Checkout verification data: \n
            Transit No.: ".$transit_no."\n
            Institution No.: ".$inst_no."\n
            Account No.: ".$account_no."\n
            Swift code: ".$swift."\n"; 
    hx_debug(ErrorLogType::CURRENCY, $msg);
    if($save_info == "Yes")
    {
        if(!empty($transit_no) && !empty($inst_no) && !empty($account_no) && !empty($swift))
        {
            $_SESSION['usd'] = round($_SESSION['usd'], 2);
            saveUseraccountInfo($conn, $_SESSION['username'], $transit_no, $inst_no, $account_no, $swift);
            $_SESSION['status'] = withdraw($conn, $_SESSION['username'], $_SESSION['usd']);
            if($_SESSION['status'] == StatusCodes::Success)
            {
                $msg = "user ".$_SESSION['username']." just withdrew ".$_SESSION['usd']." USD";
                hx_info(ErrorLogType::CURRENCY, $msg);
            }
            $_SESSION['btn_show'] = 0;
            $_SESSION['usd'] = 0;
            $_SESSION['usd'] = 0;
            $_SESSION['saved'] = 0; 
            $_SESSION['fiat'] = 0;
        }
        else
        {
            $_SESSION['status'] = StatusCodes::ErrEmpty; 
            $msg = "One of the banking information fields is empty for user ".$_SESSION['username'];
            hx_error(ErrorLogType::CURRENCY, $msg);
        }
    }
    else
    {
        if(!empty($transit_no) && !empty($inst_no) && !empty($account_no) && !empty($swift))
        {
            $_SESSION['usd'] = round($_SESSION['usd'], 2);
            $_SESSION['status'] = withdraw($conn, $_SESSION['username'], $_SESSION['usd']);
            if($_SESSION['status'] == StatusCodes::Success)
            {
                $msg = "user ".$_SESSION['username']." just withdrew ".$_SESSION['usd']." USD";
                hx_info(ErrorLogType::CURRENCY, $msg);
            }
            $_SESSION['usd'] = 0;
            $_SESSION['usd'] = 0;
            $_SESSION['fiat'] = 0;
            $_SESSION['saved'] = 0;
        }
        else
        {
            $_SESSION['status'] = StatusCodes::ErrEmpty;
            $msg = "One of the banking information fields is empty for user ".$_SESSION['username'];
            hx_error(ErrorLogType::CURRENCY, $msg);
        }
    }
    $_SESSION['dependencies'] = "FRONTEND";

    returnToMainPage();
?>