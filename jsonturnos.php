<?php
header('Content-Type: application/json; charset=utf-8');
$minxturno=3;   //minutos de atención x turno

if(!isset($_REQUEST["sucursal"])){
    echo json_encode(array("rta"=>"ERROR","msg"=>"BADDATA"));
    die();
}

include("funcionesSQL.php");
$pdo=conectoDB();

if(isset($_REQUEST["filas"]))
    $filas=" AND t.fila IN (".$_REQUEST["filas"].")";
else
    $filas="";


//Busco turnos en mis filas que no estén eliminados o agendado/pedido dentro de la última hora

$sql="SELECT t.*,s.nombre as sucursalnombre,f.nombre as filanombre, DATE_FORMAT(t.horapedido,'%d/%m %H:%i') as horapedidof, 
            FLOOR(((UNIX_TIMESTAMP(t.horapedido) - UNIX_TIMESTAMP(NOW()))/60)) as espera, c.codretiro
        FROM turnos t 
        LEFT JOIN sucursales s ON t.sucursal=s.id LEFT JOIN filas f ON t.fila=f.id LEFT JOIN turnos_codretiro c ON t.id=c.turno
        WHERE t.sucursal=:sucursal AND DATE(t.horapedido) = CURDATE() AND t.horapedido>DATE_SUB(NOW(),INTERVAL 1 HOUR) AND t.estado<>'Eliminado' $filas 
        ORDER BY t.horapedido ASC"; //agregar prioridad


//Busco turnos de mi sucursal y filas


$result=leoDB($pdo,$sql,array(":sucursal"=>$_REQUEST["sucursal"]));

echo json_encode($result);
?>
