<?php
header('Content-Type: application/json; charset=utf-8');

include("funcionesSQL.php");
$pdo=conectoDB();

if(!isset($_REQUEST["sucursal"])){
    echo json_encode(array("rta"=>"ERROR","msg"=>"BADDATA"));
    die();
}

//Esto funciona y se puede usar como seguridad
//$sessdata=json_decode($_REQUEST["sessdata"],true);

$data=array();
$idsuc=$_REQUEST["sucursal"];
$horarios_suc=leoDB($pdo,"SELECT * FROM horarios_suc WHERE sucursal=:sucursal",array(":sucursal"=>$idsuc));
$filas=leoDB($pdo,"SELECT * FROM filas",array());
$usuarios=leoDB($pdo,"SELECT * FROM usuarios",array());

$diahora=array();
$nrodia=array("dom"=>"0","lun"=>"1","mar"=>"2","mie"=>"3","jue"=>"4","vie"=>"5","sab"=>"6");
$active_dates=array();
foreach($horarios_suc as $row){
    foreach($nrodia as $k=>$v){
        if(!isset($diahora[$v]))
            $diahora[$v]=array();
        if($row[$k] == "1"){
            array_push($diahora[$v], array("abre"=>$row["abre"],"cierra"=>$row["cierra"]));
            if(!in_array($v,$active_dates))
                array_push($active_dates,$v);
        }
    }
}


$arrdias=array("dom","lun","mar","mie","jue","vie","sab");
$hoy=$arrdias[date("w")];
$hora=date('H:i:s');
$sql="SELECT abre,cierra,timediff('$hora',abre) as tiempoabre,timediff('$hora',cierra) as tiempocierra FROM horarios_suc WHERE sucursal=:sucursal AND $hoy=1";
$horarios_hoy=leoDB($pdo,$sql,array(":sucursal"=>$idsuc));

//reviso si esta abierto o cerrado 
$abierto=false;
$abre="";
$cierra="";
$tiempocierra="";
$tiempoabre="";
if(count($horarios_hoy) == 2){
    $hora0=$horarios_hoy[0];
    $hora1=$horarios_hoy[1];
    if(substr($hora0["tiempoabre"],0,1) != "-" && substr($hora0["tiempocierra"],0,1) == "-"){
        $abierto=true;
        $cierra=$hora0["cierra"];
        $tiempocierra=$hora0["tiempocierra"];
    }else if(substr($hora1["tiempoabre"],0,1) != "-" && substr($hora1["tiempocierra"],0,1) == "-"){
        $abierto=true;
        $cierra=$hora1["cierra"];
        $tiempocierra=$hora1["tiempocierra"];
    }else if(substr($hora0["tiempoabre"],0,1) == "-"){
        $abre=$hora0["abre"];
        $tiempoabre=substr($hora0["tiempoabre"],1,5);
    }else if(substr($hora1["tiempoabre"],0,1) == "-"){
        $abre=$hora1["abre"];
        $tiempoabre=substr($hora1["tiempoabre"],1,5);
    }
}else if(count($horarios_hoy) == 1){
    $hora0=$horarios_hoy[0];
    if(substr($hora0["tiempoabre"],0,1) != "-" && substr($hora0["tiempocierra"],0,1) == "-"){
        $abierto=true;
        $cierra=$hora0["cierra"];
        $tiempocierra=$hora0["tiempocierra"];
    }else if(substr($hora0["tiempoabre"],0,1) == "-"){
        $abre=$hora0["abre"];
        $tiempoabre=substr($hora0["tiempoabre"],1,5);
    }
}else  if(count($horarios_suc) == 0){
    //mal configurada la central   
    $rta=array("rta"=>"ERRROR", "msg"=>"Sucursal sin horarios de atencion");
    echo json_encode($rta);
    die();   
}

if(substr($tiempocierra,0,1) == "-")
    $tiempocierra=substr($tiempocierra,1);

$data=array("idsuc"=>$idsuc, "sucursales"=>$suc, "horarios_suc"=>$diahora, "usuarios"=>$usuarios,"filas"=>$filas, "horarios_hoy"=>$horarios_hoy , "abierto"=>$abierto, "cierra"=>$cierra, "tiempocierra"=>$tiempocierra, "abre"=>$abre, "tiempoabre"=>$tiempoabre, "active_dates"=>$active_dates);



$rta=array("rta"=>"OK", "horadispo"=>$data);

echo json_encode($rta);
?>
