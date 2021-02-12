<?php
header('Content-Type: application/json; charset=utf-8');

if(!isset($_REQUEST["id"]))
    die('{"rta":"ERROR","msg":"Faltan datos"}');


$id=$_REQUEST["id"];
if(isset($_REQUEST["minutos"]))
    $minutos=$_REQUEST['minutos'];
else
    $minutos="0";


include("funcionesSQL.php");
$pdo=conectoDB();

$sql="UPDATE turnos SET horapedido=DATE_ADD(NOW(),INTERVAL $minutos MINUTE), estado=:estado, horaatencion=:horaatencion,puesto=:puesto WHERE id=:id";
$result=escriboDB($pdo,$sql,array(":id"=>$id, ":estado"=>"Pedido", ":horaatencion"=>null, ":puesto"=>null));

if($result)
    echo json_encode(array("rta"=>"OK"));
else
    echo json_encode(array("rta"=>"ERROR","sql"=>$sql, "parametros"=>array(":id"=>$id)));

?>