<?php
header('Content-Type: application/json; charset=utf-8');
if(!isset($_REQUEST["sucursal"]) || !isset($_REQUEST["horarios"]))
    die('{"rta":"ERROR", "msg":"BADDATA"}');

$suc=$_REQUEST["sucursal"];
$horarios=$_REQUEST["horarios"];


include("funcionesSQL.php");
$pdo=conectoDB();

$parametros=array();
foreach($horarios as $h){
    array_push($parametros, array(":sucursal"=>$suc,":lun"=>$h["dias"]["lun"],":lun"=>$h["dias"]["lun"],":mar"=>$h["dias"]["mar"],
    ":mie"=>$h["dias"]["mie"],":jue"=>$h["dias"]["jue"],":vie"=>$h["dias"]["vie"],":sab"=>$h["dias"]["sab"],":dom"=>$h["dias"]["dom"],
    ":abre"=>$h["abre"],":cierra"=>$h["cierra"]));
}

$result=escriboDB($pdo,"DELETE FROM horarios_suc WHERE sucursal=:sucursal",array(":sucursal"=>$suc));
$result=insertMultipleDB($pdo,"INSERT INTO horarios_suc (sucursal, lun, mar, mie, jue, vie, sab, dom, abre, cierra) VALUES (:sucursal, :lun, :mar, :mie, :jue, :vie, :sab, :dom, :abre, :cierra);", $parametros);

if($result){
    $result2=leoDB($pdo,"select max(id) as maxid from horarios_suc",null);
    die('{"rta":"OK","msg":"Se almacenaron bien","maxid":'.$result2[0]["maxid"].'}');
}else
    echo json_encode(array("rta"=>"ERROR","msg"=>$result));

?>