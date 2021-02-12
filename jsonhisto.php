<?php
header('Content-Type: application/json; charset=utf-8');

if(!isset($_REQUEST["desde"]) || !isset($_REQUEST["hasta"])){
    echo json_encode(array("rta"=>"ERROR","msg"=>"BADDATA"));
    die();
}

include("funcionesSQL.php");
$pdo=conectoDB();

$parametros=array();
$filtro=" WHERE DATE(t.horapedido)>=:desde";
$parametros[":desde"]=$_REQUEST["desde"];
$filtro.=" AND DATE(t.horapedido)<=:hasta";
$parametros[":hasta"]=$_REQUEST["hasta"];
if(isset($_REQUEST["sucursal"])){
    $parametros[":sucursal"]=$_REQUEST["sucursal"];
    $filtro.=" AND t.sucursal=:sucursal";
}

if(isset($_REQUEST["limit"]))
    $limit="LIMIT ".$_REQUEST["limit"];
else
    $limit="LIMIT 0,10000";


$sql="SELECT t.*,s.nombre as sucursalnombre,f.nombre as filanombre, DATE_FORMAT(t.horapedido,'%d/%m %H:%i') as horapedidof, p.nombre as puestonombre, CEIL((UNIX_TIMESTAMP(t.horaatencion) - UNIX_TIMESTAMP(t.horapedido))/60) as minespera
        FROM turnos t
        LEFT JOIN sucursales s ON t.sucursal=s.id LEFT JOIN filas f ON t.fila=f.id LEFT JOIN puestos_suc p ON p.id=t.puesto
        $filtro
        ORDER BY t.horapedido DESC
        $limit";

$result=leoDB($pdo,$sql,$parametros);

echo json_encode(array("data"=>$result, "sql"=>$sql, "parametros"=>$parametros, "rta"=>((!$result)?("ERROR"):("OK"))));
?>
