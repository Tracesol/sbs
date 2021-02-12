<?php
header('Content-Type: application/json; charset=utf-8');


if(!isset($_REQUEST["puestofila"]) || !isset($_REQUEST["sucursal"]))
    die('{"rta":"ERROR", "msg":"BADDATA"}');



include("funcionesSQL.php");
$pdo=conectoDB();
$puestofila=json_decode($_POST["puestofila"],true);

$parametros=array();
$suc=$_POST["sucursal"];
foreach($puestofila as $pf){
    array_push($parametros, array(":sucursal"=>$suc,":nombre"=>$pf["nombre"],":filas"=>$pf["filas"],":usuario"=>$pf["usuario"]));
}

$result=escriboDB($pdo,"DELETE FROM puestos_suc WHERE sucursal=:sucursal",array(":sucursal"=>$suc));
$result=insertMultipleDB($pdo,"INSERT INTO puestos_suc (sucursal, nombre, filas, usuario) VALUES (:sucursal, :nombre, :filas, :usuario);", $parametros);

if($result){
    $result2=leoDB($pdo,"select max(id) as maxid from puestos_suc",null);
    die('{"rta":"OK","msg":"Se almacenaron bien","maxid":'.$result2[0]["maxid"].'}');
}else
    echo json_encode(array("rta"=>"ERROR","msg"=>$result));

?>