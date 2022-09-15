<?php
class mysql
{
    var $servidor="localhost";
    var $usuario="root";
    var $clave="13011973";
    var $db="fch";
    function conectar()
    {
        $con= new mysqli($this->servidor,$this->usuario,$this->clave,$this->db);
        return $con;
    }
    function ejecutar($sql)
    {
        $this->conectar()->query($sql);
        return true;
    }
    function consultar($sql)
    {
        $p=$this->conectar()->query($sql);
        return $p;
    }
}


?>