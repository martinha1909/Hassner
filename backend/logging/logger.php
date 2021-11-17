<?php
    function hx_error($type, $msg, $path)
    {
        $conn = connect();
        date_default_timezone_set(Timezone::MST);

        $log_msg = '['.$type.'] '.$msg."\r\n";
        $log_file = $path.'/error.log';

        $sql = "INSERT INTO error_log (log_type, message, date_logged)
                VALUES(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $type, $msg, date('Y-m-d H:i:s'));
        $stmt->execute();

        error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
    }

    function hx_info($type, $msg, $path)
    {
        $conn = connect();
        date_default_timezone_set(Timezone::MST);

        $log_msg = '['.$type.'] '.$msg."\r\n";
        $log_file = $path.'/info.log';

        $sql = "INSERT INTO info_log (log_type, message, date_logged)
                VALUES(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $type, $msg, date('Y-m-d H:i:s'));
        $stmt->execute();

        error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
    }

    function hx_debug($type, $msg, $path)
    {
        $conn = connect();
        date_default_timezone_set(Timezone::MST);

        $log_msg = '['.$type.'] '.$msg."\r\n";
        $log_file = $path.'/debug.log';

        $sql = "INSERT INTO debug_log (log_type, message, date_logged)
                VALUES(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $type, $msg, date('Y-m-d H:i:s'));
        $stmt->execute();

        error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file);
    }
?>