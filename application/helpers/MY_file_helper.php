<?php

if ( ! function_exists('get_size'))
{
    function get_size() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }
}

if ( ! function_exists('save_file'))
{
    function save_file($file_n_path) {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != get_size()){            
            return false;
        }

        $target = fopen($file_n_path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }
}

if ( ! function_exists('save_file_alt'))
{
    function save_file_alt($tmp_name="",$file_n_path="",$file_size=0) {

        if($file_size > (MAX_UPLOAD*1024)) { return FALSE; }

        if(move_uploaded_file($tmp_name, $file_n_path)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}