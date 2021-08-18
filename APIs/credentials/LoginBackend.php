<?php
    $_SESSION['dependencies'] = 1;
    include '../control/Dependencies.php'; 

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
        $_SESSION['dependencies'] = 0;
        if($row['account_type'] == "user"){
            header("Location: ../../frontend/listener/Listener.php");
            die;
        }
        else if($row['account_type'] == "artist"){
            header("Location: ../../frontend/artist/Artist.php");
            die;
        }
        else if($row['account_type'] == "admin"){
            header("Location: ../../frontend/admin/Admin.php");
            die;
        }
    }
    else
    {
        $_SESSION['dependencies'] = 0;
        header("Location: ../../frontend/credentials/login.php");
    }

    closeCon($conn); 


?>