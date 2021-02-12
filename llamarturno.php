<?php
header('Content-Type: application/json; charset=utf-8');

if(!isset($_REQUEST["puesto"]))
    die('{"rta":"ERROR","msg":"BADDATA"}');

$puesto=$_REQUEST["puesto"];

include("funcionesSQL.php");
$pdo=conectoDB();

if(isset($_REQUEST["usuario"]))
    $usuario=$_REQUEST["usuario"];
else
    $usuario=0;


$sql="SELECT filas FROM puestos_suc WHERE id=$puesto";
$filas=leoDBDato($pdo,$sql);
//prox turno
$sql="SELECT * FROM turnos WHERE estado='Pedido' AND fila IN (".$filas.") AND horapedido>DATE_SUB(NOW(),INTERVAL 1 HOUR) AND horapedido<NOW() AND (puesto is null or puesto=$puesto) AND agendado='Si' ORDER BY horapedido ASC LIMIT 1";
$result=leoDB($pdo,$sql,array(":fila"=>$puesto));

if($result[0]["id"] == null){
    $sql="SELECT * FROM turnos WHERE estado='Pedido' AND fila IN (".$filas.") AND horapedido>DATE_SUB(NOW(),INTERVAL 1 HOUR) AND horapedido<NOW() AND (puesto is null or puesto=$puesto) ORDER BY horapedido ASC LIMIT 1";
    $result=leoDB($pdo,$sql,array(":fila"=>$puesto));
}

$turno="";
$codigo="";
$fila="";
$sucursal="";

if($result[0]["id"] == null){
    die('{"llamado":{"codigo":null,"sql":"'.$filas.'"}}');
}else{
    $turno=$result[0]["id"];
    $codigo=$result[0]["codigo"];
    $fila=$result[0]["fila"];
    $sucursal=$result[0]["sucursal"];
    $sql2="UPDATE turnos SET estado=:estado, puesto=:puesto, horaatencion=NOW() WHERE id=:id";
    $result2=escriboDB($pdo,$sql2,array(":id"=>$turno, ":estado"=>"Llamado", ":puesto"=>$puesto ));
    $sql2="INSERT INTO turnos_log (turno, estado, puesto, usuario) VALUES (turno, estado, puesto, usuario)";
    $result2=escriboDB($pdo,$sql2,array(":turno"=>$turno, ":estado"=>"Llamado", ":puesto"=>$puesto, ":usuario"=>$usuario ));    
}



$rta=array();
$rta["llamado"]=array("turno"=>$turno, "codigo"=>$codigo,"fila"=>$fila,"sucursal"=>$sucursal,"puesto"=>$puesto);
echo json_encode($rta);
?>