<?php
        $host = 'localhost';
        $user = 'hassner'; // Running locally user: root pw: ""
        $pwd = 'bingus123';
        $db = 'hassner';
        $db_test = 'hassner_test';
        $user_test = "root";
        $pwd_test = "";

        $conn;

        function connect() {
            global $host, $user, $pwd, $db;
            $conn = mysqli_connect($host, $user, $pwd, $db);
            return $conn;
        }

        function connectPDO()
        {
            global $user, $pwd;
            $dsn = "mysql:host=localhost;dbname=hassner";
            try
            {
                $conn = new PDO($dsn, $user, $pwd, array(PDO::ATTR_PERSISTENT => true)); 
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $conn;
            }
            catch(PDOException $e)
            {
                $err_msg = $e->getMessage();
                echo $err_msg;
            }
        }

        function connectTest() {
            global $host, $user_test, $pwd_test, $db_test;
            $conn = mysqli_connect($host, $user_test, $pwd_test, $db_test);
            return $conn;
        }

        function connectPDOTest()
        {
            global $user_test, $pwd_test;
            $dsn = "mysql:host=localhost;dbname=hassner_test";
            try
            {
                $conn = new PDO($dsn, $user_test, $pwd_test, array(PDO::ATTR_PERSISTENT => true)); 
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $conn;
            }
            catch(PDOException $e)
            {
                $err_msg = $e->getMessage();
                echo $err_msg;
            }
        }

        function closeCon($conn)
        {
            $conn->close();
        }


?>
