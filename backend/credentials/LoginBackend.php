<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php'; 
    include '../constants/AccountTypes.php';
    include '../constants/StatusCodes.php';

    $conn = connect();
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = login($conn,$username,$password);
    if ($result->num_rows > 0) {
    
        $row = mysqli_fetch_assoc($result);

        $_SESSION['account_type'] = $row['account_type'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['password'] = $row['password'];
        $_SESSION['id'] = $row['id'];
        $_SESSION['dependencies'] = "FRONTEND";
        if($row['account_type'] == AccountType::User){
            header("Location: ../../frontend/listener/Listener.php");
            die;
        }
        else if($row['account_type'] == AccountType::Artist){
            header("Location: ../../frontend/artist/Artist.php");
            die;
        }
        else if($row['account_type'] == AccountType::Admin){
            header("Location: ../../frontend/admin/Admin.php");
            die;
        }
    }
    else
    {
        $_SESSION['dependencies'] = "FRONTEND";
        $_SESSION['status'] = StatusCodes::ErrGeneric;
        header("Location: ../../frontend/credentials/login.php");
    }

      


?>