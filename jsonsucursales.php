<?php
header('Content-Type: application/json; charset=utf-8');

include("funcionesSQL.php");
$pdo=conectoDB();
$sql="SELECT id,nombre,prioridad,tipo FROM filas";
$result=leoDB($pdo,$sql,null);
$filas=array();
foreach($result as $row)
    $filas[$row["id"]]=$row;

$sql="SELECT * FROM sucursales";
$sql2="SELECT id,nombre,filas FROM puestos_suc WHERE  sucursal=:sucursal";
$result=leoDB($pdo,$sql,null);
$sucursales=array();
foreach($result as $row){//sucursal
    $result2=leoDB($pdo,$sql2,array(":sucursal"=>$row["id"]));
    $filas_suc=array();
    $puestos=array();
    foreach($result2 as $row2){//puesto
        $arrfilas=explode(",",$row2["filas"]);
        $filapuesto=array();
        foreach($arrfilas as $f){//fila del puesto
            if(!in_array($filas[$f],$filas_suc))
                array_push($filas_suc,$filas[$f]);
            array_push($filapuesto,$filas[$f]);
        }
        $row2["filas"]=$filapuesto;
        array_push($puestos,$row2);
    }
    array_push($sucursales, array("id"=>$row["id"],"sucnombre"=>$row["nombre"],"nombre"=>$row["nombre"],"telefono"=>$row["telefono"],"direccion"=>$row["direccion"],"puestos"=>$puestos, "filas"=>$filas_suc));
}

echo json_encode($sucursales);
?>
