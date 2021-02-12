<?php
header('Content-Type: application/json; charset=utf-8');

if(!isset($_REQUEST["puesto"]) || !isset($_REQUEST["estado"]) || !isset($_REQUEST["id"]))
    die('{"rta":"ERROR","msg":"BADDATA"}');

$puesto=$_REQUEST["puesto"];
$estado=$_REQUEST["estado"];
$id=$_REQUEST["id"];

if(isset($_REQUEST["usuario"]))
    $usuario=$_REQUEST["usuario"];
else
    $usuario=0;


include("funcionesSQL.php");
$pdo=conectoDB();
    

$sql="UPDATE turnos SET estado=:estado, puesto=:puesto,horaatencion=NOW() WHERE id=:id";
$result=escriboDB($pdo,$sql,array(":id"=>$_REQUEST["id"], ":puesto"=>$puesto, ":estado"=>$estado));
$sql2="INSERT INTO turnos_log (turno, estado, puesto, usuario) VALUES (turno, estado, puesto, usuario)";
$result2=escriboDB($pdo,$sql2,array(":turno"=>$id, ":estado"=>$estado, ":puesto"=>$puesto, ":usuario"=>$usuario ));    

if(!$result){
    echo json_encode(array("rta"=>"ERROR","msg"=>$sql." ".var_export($result,true)));
}else{
    echo json_encode(array("rta"=>"OK"));
}

?>