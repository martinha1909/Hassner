<?php

    session_start();
    include 'connection.php';
    include 'logic.php';    

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
            if($row['account_type'] == "user"){
                header("Location: ../frontend/listener/listener.php");
                die;
            }
            else if($row['account_type'] == "artist"){
                header("Location: ../frontend/artist/artist.php");
                die;
            }
            else if($row['account_type'] == "admin"){
                header("Location: ../frontend/admin/admin.php");
                die;
            }
           
        }
        else
        {
            header("Location: ../frontend/login.php");
        }

    closeCon($conn); 


?>