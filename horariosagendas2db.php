<?php
header('Content-Type: application/json; charset=utf-8');

if(!isset($_REQUEST["datos"]) || !isset($_REQUEST["puesto"]))
    die('{"rta":"ERROR","msg":"Faltan datos"}');

$datos=json_decode($_REQUEST["datos"],true);
$puesto=$_REQUEST["puesto"];

include("funcionesSQL.php");
$pdo=conectoDB();
$result=escriboDB($pdo,"DELETE FROM horariosagendas WHERE puesto=:puesto",array(":puesto"=>$puesto));
$horariosagendas=array();
foreach($datos as $d){
    array_push($horariosagendas,array(":puesto"=>$puesto,":dia"=>$d["dia"],":hini"=>$d["hini"],":hfin"=>$d["hfin"],":nota"=>$d["nota"]));
}

$result=insertMultipleDB($pdo,"INSERT INTO horariosagendas (puesto, dia, hini, hfin, nota) VALUES (:puesto, :dia, :hini, :hfin, :nota)",$horariosagendas);

if($result)
    die('{"rta":"OK","msg":"Se almacenaron bien"}');
else
    die('{"rta":"ERROR","msg":"Error al guardar"}');

?>