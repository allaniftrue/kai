<?php
function get_size() {
    if (isset($_SERVER["CONTENT_LENGTH"])){
        return (int)$_SERVER["CONTENT_LENGTH"];            
    } else {
        throw new Exception('Getting content length is not supported.');
    }      
}

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