<?php
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
?>