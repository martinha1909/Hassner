<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php'; 
    include '../constants/AccountTypes.php';
    include '../constants/StatusCodes.php';

    $conn = connect();
    $username = $_POST['username'];
    $password = $_POST['password'];


    $result = login($conn,$username,$password);
    if ($result->num_rows > 0) 
    {
    
        $row = mysqli_fetch_assoc($result);

        $_SESSION['account_type'] = $row['account_type'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['id'] = $row['id'];
        $_SESSION['dependencies'] = "FRONTEND";
        echo(json_encode(array(            
            "status"=> 1,
            "msg"=> "Success"
        )));
    }
    else
    {
        $msg = "Credentials not found";
        hx_error(HX::LOGIN, $msg);

        $_SESSION['dependencies'] = "FRONTEND";
        echo(json_encode(array(            
            "status"=> "0",
            "msg"=> "Invalid Login"
        )));
    }

?>