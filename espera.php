<?php
header('Content-Type: application/json; charset=utf-8');

if(isset($_REQUEST["usr_id"]))
    $usr_id=$_REQUEST["usr_id"];
else
    die('{"rta":"ERROR","msg":"BADDATA"}');

include("funcionesSQL.php");
$pdo=conectoDB();

$ressuc=leoDB($pdo,"SELECT * FROM sucursales",array());
$resfilas=leoDB($pdo,"SELECT * FROM filas",array());
$respuesto=leoDB($pdo,"SELECT * FROM puestos_suc",array());
$suc=array();
foreach($ressuc as $row)
    $suc[$row["id"]]=$row["nombre"];
$filas=array();
foreach($resfilas as $row)
    $filas[$row["id"]]=$row["nombre"];
$puestos=array();
foreach($respuesto as $row)
    $puestos[$row["id"]]=$row["nombre"];
                
//Turnos no programados
$sql="SELECT id,codigo,horapedido,estado,fila,puesto,sucursal,usr_id,UNIX_TIMESTAMP(horapedido) as horapedidox, DATE_FORMAT(horapedido,'%d/%m %H:%i') as horapedidof,
        ((UNIX_TIMESTAMP(horapedido) - UNIX_TIMESTAMP(NOW()))/60) as minespera, agendado
        FROM turnos 
        WHERE DATE(horapedido)>=CURDATE() AND estado<>'Eliminado' AND horapedido > DATE_SUB(NOW(), INTERVAL 60 MINUTE)
        ORDER BY horapedido ASC";
$result=leoDB($pdo,$sql,array(":usr_id"=>$usr_id));

$turnoshoy=array();
$i=1;
foreach($result as $row){
    $espera=3;

    if($row["usr_id"] == $usr_id){     
        array_push($turnoshoy,array("id"=>$row["id"],"codigo"=>$row["codigo"], "estado"=>$row["estado"], "fila"=>$row["fila"], "filanombre"=>$filas[$row["fila"]],
        "puesto"=>$row["puesto"],  "puestonombre"=>$puestos[$row["puesto"]], "sucursal"=>$row["sucursal"], "sucursalnombre"=>$suc[$row["sucursal"]],
        "usr_id"=>$row["usr_id"], "espera"=>intval($espera), "horapedido"=>$row["horapedido"], "horapedidof"=>$row["horapedidof"], "agendado"=>$row["agendado"],
        "horapedidox"=>($row["horapedidox"] * 1000), "minespera"=>intval($row["minespera"]), "posicion"=>$i));
    }
    if($row["estado"] == "Pedido")
        $i++;
}
echo json_encode($turnoshoy);
?>