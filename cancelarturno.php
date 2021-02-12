<?php
header('Content-Type: application/json; charset=utf-8');

if(!isset($_REQUEST["id"]))
    die('{"rta":"ERROR","msg":"Faltan datos"}');

$id=$_REQUEST["id"];

include("funcionesSQL.php");
$pdo=conectoDB();

$sql="UPDATE turnos SET estado='Eliminado',puesto=null,horaatencion=null WHERE id=:id";
$result=escriboDB($pdo,$sql,array(":id"=>$id));

if($result)
    echo json_encode(array("rta"=>"OK"));
else
    echo json_encode(array("rta"=>"ERROR","sql"=>$sql, "parametros"=>array(":codigo"=>$codigo)));

?>