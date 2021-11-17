<?php
    function hx_error($type, $msg)
    {
        $date_logged = date('Y-m-d H:i:s');
        $path = 0;
        $log_file = 0;
        if($_SESSION['dependencies'] == "FRONTEND")
        {
            $path = ErrorLogPath::FRONTEND;
        }
        else if($_SESSION['dependencies'] == "BACKEND")
        {
            $path = ErrorLogPath::BACKEND;
        }

        $conn = connect();
        date_default_timezone_set(Timezone::MST);

        $log_msg = '['.$type.'] '.$msg."\r\n";
        if($path != 0)
        {
            $log_file = $path.'/error.log';
        }

        if($log_file != 0)
        {
            $sql = "INSERT INTO error_log (log_type, message, date_logged)
                    VALUES(?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $type, $msg, $date_logged);
            $stmt->execute();

            error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
        }
    }

    function hx_info($type, $msg)
    {
        $date_logged = date('Y-m-d H:i:s');
        $path = 0;
        $log_file = 0;
        if($_SESSION['dependencies'] == "FRONTEND")
        {
            $path = ErrorLogPath::FRONTEND;
        }
        else if($_SESSION['dependencies'] == "BACKEND")
        {
            $path = ErrorLogPath::BACKEND;
        }
        $conn = connect();
        date_default_timezone_set(Timezone::MST);

        $log_msg = '['.$type.'] '.$msg."\r\n";
        if($path != 0)
        {
            $log_file = $path.'/info.log';
        }

        if($log_file != 0)
        {
            $sql = "INSERT INTO info_log (log_type, message, date_logged)
                    VALUES(?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $type, $msg, $date_logged);
            $stmt->execute();

            error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
        }
    }

    function hx_debug($type, $msg)
    {
        $date_logged = date('Y-m-d H:i:s');
        $path = 0;
        $log_file = 0;
        if($_SESSION['dependencies'] == "FRONTEND")
        {
            $path = ErrorLogPath::FRONTEND;
        }
        else if($_SESSION['dependencies'] == "BACKEND")
        {
            $path = ErrorLogPath::BACKEND;
        }
        $conn = connect();
        date_default_timezone_set(Timezone::MST);

        $log_msg = '['.$type.'] '.$msg."\r\n";
        if($path != 0)
        {
            $log_file = $path.'/debug.log';
        }

        if($log_file != 0)
        {
            $sql = "INSERT INTO debug_log (log_type, message, date_logged)
                    VALUES(?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $type, $msg, $date_logged);
            $stmt->execute();

            error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
        }
    }
?>