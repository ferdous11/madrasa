<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('savelog')) {

    function savelog($action, $details) {
        $CI = & get_instance();
        $logdataarray = array(
            'action' => $action,
            'details' => $details,
            'date' => date("Y-m-d H:i:s")
        );
        $savestatus = $CI->db->insert("activity_log", $logdataarray);
    }

}

if (!function_exists('validate')) {

    function validate() {
        $CI = & get_instance();
        $server = md5($_SERVER['HTTP_HOST']);
        $serverip = md5($_SERVER['SERVER_ADDR']);
        $checkquery = $CI->db->query("select * from config where server = '$server' AND ip = '$serverip'");
        if ($checkquery->num_rows() > 0):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

}
if (!function_exists('dataview')) {
    function dataview($parray) {
        echo "<pre>";
        print_r($parray);
        echo "</pre>";
        die();
        return true;
    }
}

?>