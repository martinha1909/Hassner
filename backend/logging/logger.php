<?php
    function db_info($conn, $type, $msg, $date)
    {
        $sql = "INSERT INTO info_log (log_type, message, date_logged)
                VALUES(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $type, $msg, $date);
        $stmt->execute();
    }

    function db_error($conn, $type, $msg, $date)
    {
        $sql = "INSERT INTO error_log (log_type, message, date_logged)
                VALUES(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $type, $msg, $date);
        $stmt->execute();
    }

    function db_debug($conn, $type, $msg, $date)
    {
        $sql = "INSERT INTO debug_log (log_type, message, date_logged)
                VALUES(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $type, $msg, $date);
        $stmt->execute();
    }

    function hx_error($type, $msg, $path)
    {
        $conn = connect();
        date_default_timezone_set('America/Edmonton');

        $log_msg = '['.$type.'] '.$msg."\r\n";
        $log_file = $path.'/error.log';

        error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
        db_error($conn, $type, $msg, date('Y-m-d H:i:s'));
    }

    function hx_info($type, $msg, $path)
    {
        $conn = connect();
        date_default_timezone_set('America/Edmonton');

        $log_msg = '['.$type.'] '.$msg."\r\n";
        $log_file = $path.'/info.log';

        error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
        db_info($conn, $type, $msg, date('Y-m-d H:i:s'));
    }

    function hx_debug($type, $msg, $path)
    {
        $conn = connect();
        date_default_timezone_set('America/Edmonton');

        $log_msg = '['.$type.'] '.$msg."\r\n";
        $log_file = $path.'/debug.log';

        error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
        db_debug($conn, $type, $msg, date('Y-m-d H:i:s'));
    }
?>