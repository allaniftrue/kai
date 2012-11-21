<?php
#$result->result_array()
function elements_only($array,$index) {
    if(is_array($array)) {
        $total = count($array);
        if($total == 0){
            return FALSE;
        } else {
            $tmp = array();
            for($i=0; $i<$total; $i++){
                array_push($tmp, $array[$i][$index]);
            }
            return $tmp;
        }
    }else {
        return FALSE;
    }
}