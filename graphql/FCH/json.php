<?php
class json{
    function convertir($query){
        $data=array();
        while($r = $query->fetch_assoc()){
            $data[] = $r;
        }
        return $data;
    }
}
?>