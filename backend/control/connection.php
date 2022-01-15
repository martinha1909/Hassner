<?php
        $host = 'localhost';
        $user = 'root';
        $pwd = 'sGVZgh3s1OG2';
        $db = 'hassner';

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

        function closeCon($conn)
        {
            $conn->close();
        }


?>
