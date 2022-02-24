<?php

    function _concat_log($log_level, $log_type, $log_msg)
    {
        date_default_timezone_set(Timezone::MST);
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

        $log_msg = '['.$log_level.']-['.$log_type.']-['.$error_file.'@'.$error_line.']'.$log_msg."\r\n";
        if($path != 0)
        {
            $log_file = $path.'/hx.log';
        }

        if($log_file != 0)
        {
            $sql = "INSERT INTO hx_log (log_level, log_type, log_msg, log_file, log_line, date_logged)
                    VALUES(?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssis', $log_level, $log_type, $log_msg, $error_file, $error_line, $date_logged);
            $stmt->execute();

            error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
        }
    }

    function hx_error($type, $msg)
    {
        if($_SESSION['error'])
        {
            _concat_log(LogLevel::DEBUG, $type, $msg);
        }
    }

    function hx_info($type, $msg)
    {
        if($_SESSION['info'])
        {
            _concat_log(LogLevel::INFO, $type, $msg);
            // date_default_timezone_set(Timezone::MST);
            // $error_trace = debug_backtrace();
            // //We only care about the last stack trace
            // $error_file = pathinfo($error_trace[0]['file'])['basename'];
            // $error_line = $error_trace[0]['line'];
            // $date_logged = date('Y-m-d H:i:s');
            // $path = 0;
            // $log_file = 0;
            // if($_SESSION['dependencies'] == "FRONTEND")
            // {
            //     $path = ErrorLogPath::FRONTEND;
            // }
            // else if($_SESSION['dependencies'] == "BACKEND")
            // {
            //     $path = ErrorLogPath::BACKEND;
            // }
            // $conn = connect();

            // $log_msg = '['.$type.']-['.$error_file.'@'.$error_line.']'.$msg."\r\n";
            // if($path != 0)
            // {
            //     $log_file = $path.'/hx.log';
            // }

            // if($log_file != 0)
            // {
            //     $sql = "INSERT INTO info_log (log_type, message, log_file, log_line, date_logged)
            //     VALUES(?, ?, ?, ?, ?)";
            //     $stmt = $conn->prepare($sql);
            //     $stmt->bind_param('sssis', $type, $msg, $error_file, $error_line, $date_logged);
            //     $stmt->execute();

            //     error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
            // }
        }
    }

    function hx_debug($type, $msg)
    {
        if($_SESSION['debug'])
        {
            _concat_log(LogLevel::DEBUG, $type, $msg);
            // date_default_timezone_set(Timezone::MST);
            // $error_trace = debug_backtrace();
            // //We only care about the last stack trace
            // $error_file = pathinfo($error_trace[0]['file'])['basename'];
            // $error_line = $error_trace[0]['line'];
            // $date_logged = date('Y-m-d H:i:s');
            // $path = 0;
            // $log_file = 0;
            // if($_SESSION['dependencies'] == "FRONTEND")
            // {
            //     $path = ErrorLogPath::FRONTEND;
            // }
            // else if($_SESSION['dependencies'] == "BACKEND")
            // {
            //     $path = ErrorLogPath::BACKEND;
            // }
            // $conn = connect();

            // $log_msg = '['.$type.']-['.$error_file.'@'.$error_line.']'.$msg."\r\n";
            // if($path != 0)
            // {
            //     $log_file = $path.'/hx.log';
            // }

            // if($log_file != 0)
            // {
            //     $sql = "INSERT INTO debug_log (log_type, message, log_file, log_line, date_logged)
            //             VALUES(?, ?, ?, ?, ?)";
            //     $stmt = $conn->prepare($sql);
            //     $stmt->bind_param('sssis', $type, $msg, $error_file, $error_line, $date_logged);
            //     $stmt->execute();

            //     error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
            // }
        }
    }
?>