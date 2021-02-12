<?php
header('Content-Type: application/json; charset=utf-8');

if(!isset($_REQUEST["sucursal"])){
    echo json_encode(array("rta"=>"ERROR","msg"=>"BADDATA"));
    die();
}

include("funcionesSQL.php");
$pdo=conectoDB();
$idsuc=$_REQUEST["sucursal"];
$filtro="";
$duracion_turno=10;
if(isset($_REQUEST["filas"]))
    $filtro.=" AND t.fila IN (".$_REQUEST["filas"].")";

if(isset($_REQUEST["dia"])){
    $filtro.=" AND DATE(t.horapedido) = '".$_REQUEST['dia']."'";
    $wday=date("w" , strtotime($_REQUEST['dia']));
    $ts=date("U" , strtotime($_REQUEST['dia']));
    $eshoy=false;
}else{
    $filtro.=" AND DATE(t.horapedido) = CURDATE() ";
    $wday=date("w");
    $ts=date("U" , strtotime(date('Y-m-d 00:00:00')));
    $eshoy=true;
    $ahora=date("U");
}

$sql="SELECT DATE_FORMAT(t.horapedido,'%H:%i') as horapedidof, count(*) as cant FROM turnos t 
        WHERE t.sucursal=:sucursal AND t.estado='Pedido' AND t.horapedido like '%:_0:00' $filtro 
        GROUP BY t.horapedido"; //agregar prioridad
$result=leoDB($pdo,$sql,array(":sucursal"=>$idsuc));

$result_horarios=leoDB($pdo,"SELECT * FROM horarios_suc WHERE sucursal=:sucursal",array(":sucursal"=>$idsuc));
$puestos_suc=leoDB($pdo,"SELECT * FROM puestos_suc WHERE sucursal=:sucursal",array(":sucursal"=>$idsuc));
$cantpuestos=0;
foreach($puestos_suc as $row){
    $f=explode(",",$row["filas"]);
    if(in_array($_REQUEST["filas"], $f))
        $cantpuestos++;
}


$dias=["dom","lun","mar","mie","jue","vie","sab"];
$horarios=array();
foreach($result_horarios as $row){
    if($row[$dias[$wday]] == "1"){
        $a=explode(":" , $row["abre"]);
        $c=explode(":" , $row["cierra"]);
        $abre=$ts+$a[0]*3600+$a[1]*60;
        $cierra=$ts+$c[0]*3600+$c[1]*60;
        if($eshoy){
            if($ahora > $cierra)
                $abre = $cierra;
            if($abre < $ahora)
                $abre=date("U",$ahora+(3600 - $ahora%3600));
        }
        for($i=$abre;$i<$cierra;$i+=$duracion_turno*60){
            $agregar=true;
            foreach($result as $rowturnos){
                $x=date("H:i",$i);
                if($rowturnos["horapedidof"] == $x && $rowturnos["cant"] >= $cantpuestos)
                    $agregar=false;
            }
            if($agregar)
                array_push($horarios,date("H:i",$i));
        }
    }
}

echo json_encode(array("rta"=>"OK","agendados"=>$result, "horarios"=>$horarios));
?>
