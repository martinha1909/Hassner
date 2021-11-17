<?php
    function hx_error($type, $msg)
    {
        $error_trace = debug_backtrace();
        //We only care about the last stack trace
        $error_file = pathinfo($error_trace[0]['file'])['basename'];
        $error_line = $error_trace[0]['line'];
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

        $log_msg = '['.$type.']-['.$error_file.'@'.$error_line.']'.$msg."\r\n";
        if($path != 0)
        {
            $log_file = $path.'/error.log';
        }

        if($log_file != 0)
        {
            $sql = "INSERT INTO error_log (log_type, message, log_file, log_line, date_logged)
                    VALUES(?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssis', $type, $msg, $error_file, $error_line, $date_logged);
            $stmt->execute();

            error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
        }
    }

    function hx_info($type, $msg)
    {
        $error_trace = debug_backtrace();
        //We only care about the last stack trace
        $error_file = pathinfo($error_trace[0]['file'])['basename'];
        $error_line = $error_trace[0]['line'];
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

        $log_msg = '['.$type.']-['.$error_file.'@'.$error_line.']'.$msg."\r\n";
        if($path != 0)
        {
            $log_file = $path.'/info.log';
        }

        if($log_file != 0)
        {
            $sql = "INSERT INTO info_log (log_type, message, log_file, log_line, date_logged)
            VALUES(?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssis', $type, $msg, $error_file, $error_line, $date_logged);
            $stmt->execute();

            error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
        }
    }

    function hx_debug($type, $msg)
    {
        $error_trace = debug_backtrace();
        //We only care about the last stack trace
        $error_file = pathinfo($error_trace[0]['file'])['basename'];
        $error_line = $error_trace[0]['line'];
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

        $log_msg = '['.$type.']-['.$error_file.'@'.$error_line.']'.$msg."\r\n";
        if($path != 0)
        {
            $log_file = $path.'/debug.log';
        }

        if($log_file != 0)
        {
            $sql = "INSERT INTO debug_log (log_type, message, log_file, log_line, date_logged)
                    VALUES(?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssis', $type, $msg, $error_file, $error_line, $date_logged);
            $stmt->execute();

            error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
        }
    }
?>