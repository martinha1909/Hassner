<?php
    include '../../../backend/control/connection.php';

    $pwd = $_POST['verify_password'];
    $pwd_hash_str = "$2y$10$5fzV7dVBevglni99U3TnOePLaH07Hg75gJvsqdhtF9hKAq4QbGjHm";
    if(password_verify($pwd, $pwd_hash_str))
    {
        echo json_encode(array(
            "status" => "SUCCESS",
            "msg" => ""
        ));
    }
    else
    {
        echo(json_encode(array(            
            "status"=> "ERROR",
            "msg"=> "Wrong password"
        )));
    }
?>