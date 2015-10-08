<?php

ini_set('max_execution_time', 150);
if (!defined('DP_API_KEY')) {define('DP_API_KEY', 'eOk/439lMZOZrzsOkg25Vknt3zjNZ65TRX1U41vXMi8WTZDaaEDpkNReEkM/6/NGfO1SOZKoWsyTONA4q8OUGQ==');}
//if (!defined('DP_API_KEY')) {define('DP_API_KEY', 'Ch4taGHGPTZw/1fmJzOqlrPeWJRZ1w9K1yPqfXvX6i8HTTXXv/Vk/Tb8sgn72mhMfm6OqKCkqlHkGDDl/veLzA==');}
// Dont Edit the Code below.
if (!defined('DP_MAIN_URL')) {define('DP_MAIN_URL', 'http://decideprice.com/api/theme/');}

class decideprice_theme
{
    function __construct()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, DP_MAIN_URL . 'check-for-changes');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40000); //timeout in seconds
        $headers   = array();
        $headers[] = 'Authorization:' . DP_API_KEY;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output   = curl_exec($ch);
        $last_saved_time = json_decode($server_output, true);
        if ($last_saved_time['result'] != NULL) {
            if (file_exists('decideprice-api-php-theme/cache/force_load.json')) {
                $force_load = @fopen('decideprice-api-php-theme/cache/force_load.json', 'r') or die("Unable to open file!");
                $last_updated_change = json_decode(@fread($force_load, filesize("decideprice-api-php-theme/cache/force_load.json")), true);
                if ($last_updated_change['result'] != $last_saved_time['result']) {
                    $files = glob('decideprice-api-php-theme/cache/*'); // get all file names
                    foreach ($files as $file) { // iterate files
                        if (is_file($file) && $file != 'decideprice-api-php-theme/cache/force_load.json')
                            unlink($file); // delete file
                    }
                    $fp = @fopen('decideprice-api-php-theme/cache/force_load.json', 'w');
                    @fwrite($fp, $server_output);
                    @fclose($fp);
                }
                @fclose($force_load);
            } else {
                $fp = @fopen('decideprice-api-php-theme/cache/force_load.json', 'w');
                @fwrite($fp, $server_output);
                @fclose($fp);
            }
        }
    }
    function get($request)
    {
        $file_name = str_replace("/", "_", $request);
        if (file_exists('decideprice-api-php-theme/cache/' . $file_name . '.json')) {
            $myfile = @fopen('decideprice-api-php-theme/cache/' . $file_name . '.json', 'r') or die("Unable to open file!");
            $old_result = json_decode(@fread($myfile, filesize("decideprice-api-php-theme/cache/" . $file_name . ".json")), true);
            @fclose($myfile);
            return json_decode($old_result[$request], true);
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, DP_MAIN_URL . $request);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 40000); //timeout in seconds
            $headers   = array();
            $headers[] = 'Authorization:' . DP_API_KEY;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $server_output = curl_exec($ch);
            $array         = array(
                $request => $server_output
            );
            if (strpos($file_name, 'popular-products') === false && strpos($file_name, 'search') === false && strpos($file_name, 'deals') === false) {
                $fp = @fopen('decideprice-api-php-theme/cache/' . $file_name . '.json', 'w');
                @fwrite($fp, json_encode($array));
                @fclose($fp);
            }
            return json_decode($server_output, true);
        }
    }
}