<?php
        $host = 'localhost';
        $user = 'root';
        $pwd = '';
        $db = 'hassner';

        $conn;


        function connect() {
            global $host, $user, $pwd, $db;
            $conn = mysqli_connect($host, $user, $pwd, $db);
            return $conn;
        }

        function closeCon($conn)
        {
            $conn->close();
        }


?>