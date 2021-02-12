<?php
header('Content-Type: application/json; charset=utf-8');

if(!isset($_REQUEST["fila"]))
    die('{"rta":"ERROR","msg":"Faltan datos"}');

$fila=$_REQUEST["fila"];
$sucursal=$_REQUEST["sucursal"];
if(isset($_REQUEST["usr_id"]) && $_REQUEST["usr_id"] != "0"){
    $usr_id=$_REQUEST["usr_id"];
}else{
    $usr_id=md5(Date("YmdHis").random_int(100,999));
}

include("funcionesSQL.php");
$pdo=conectoDB();

$sql2="SELECT count(*) as cuenta FROM turnos WHERE estado='Pedido'";
$result2=leoDB($pdo,$sql2,null);
$espera=intval($result2[0]["cuenta"])*3;

$sql="SELECT id,codigo FROM turnos WHERE fila=:fila ORDER BY id DESC LIMIT 1";
$result=leoDB($pdo,$sql,array(":fila"=>$fila));

$cod=chr(65+intval($fila)).((!isset($result[0]["id"]))?(0):($result[0]["id"]));

$fechahoraX=date("U", strtotime($_REQUEST["fechahora"]));
$horaX=date("U");
$agendado="No";
if(isset($_REQUEST["fechahora"])){
    if($fechahoraX > ($horaX - 30)){
        if($fechahoraX >  ($horaX + 30*60)){
            $agendado="Si"; //si pide para despues de media hora, lo tomo como agendado
        }
        $sql="INSERT INTO turnos (codigo,fila,horapedido,sucursal,usr_id,agendado) VALUES (:codigo,:fila,:horapedido,:sucursal,:usr_id,:agendado)";
        $result=escriboDB($pdo,$sql,array(":fila"=>$fila, ":sucursal"=>$sucursal, ":codigo"=>$cod, ":horapedido"=>$_REQUEST["fechahora"], ":usr_id"=>$usr_id, ":agendado"=>$agendado));
    }else{
        //pide una hora que ya paso
        echo json_encode(array("rta"=>"ERROR","msg"=>"Horario pasado"));
        die();        
    }
}else{
    $sql="INSERT INTO turnos (codigo,fila,sucursal,usr_id,agendado) VALUES (:codigo,:fila,:sucursal,:usr_id,:agendado)";
    $result=escriboDB($pdo,$sql,array(":fila"=>$fila, ":sucursal"=>$sucursal, ":codigo"=>$cod, ":usr_id"=>$usr_id,":agendado"=>"No"));
}


if(isset($_REQUEST["codretiro"])){
    $sql="SELECT max(id) as idmax FROM turnos";
    $maxid=leoDBDato($pdo,$sql);
    $sql2="INSERT INTO turnos_codretiro (turno,codretiro) VALUES (:turno,:codretiro)";
    $result2=escriboDB($pdo,$sql2,array(":turno"=>$maxid,":codretiro"=>$_REQUEST["codretiro"]));        
}


if($result)
    echo json_encode(array("rta"=>"OK", "fila"=>$fila, "sucursal"=>$sucursal, "codigo"=>$cod, "espera"=>$espera,"usr_id"=>$usr_id));
else
    echo json_encode(array("rta"=>"ERROR","sql"=>$sql, "parametros"=>array(":fila"=>$fila, ":sucursal"=>$sucursal, ":codigo"=>$cod,":usr_id"=>$usr_id, "result"=>$result)));

?>