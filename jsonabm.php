<?php
header('Content-Type: application/json; charset=utf-8');

include("funcionesSQL.php");
$pdo=conectoDB();

if(!isset($_REQUEST["tipo"])){
    echo json_encode(array("rta"=>"ERROR","msg"=>"BADDATA"));
    die();
}

//Esto funciona y se puede usar como seguridad
//$sessdata=json_decode($_REQUEST["sessdata"],true);

$tipo=$_REQUEST["tipo"];

$data=array();
if($tipo =="usuarios"){
    $data=leoDB($pdo,"SELECT * FROM usuarios",array());
}else if($tipo =="sucursales"){
    $data=leoDB($pdo,"SELECT * FROM sucursales",array());
}else if($tipo =="filas"){
    $data=leoDB($pdo,"SELECT * FROM filas",array());
}else if($tipo =="horarios"){
    $idsuc=$_REQUEST["idsuc"];
    $suc=leoDB($pdo,"SELECT * FROM sucursales WHERE id=:id",array(":id"=>$idsuc));
    $horarios_suc=leoDB($pdo,"SELECT * FROM horarios_suc WHERE sucursal=:sucursal",array(":sucursal"=>$idsuc));
    $usuarios_suc=leoDB($pdo,"SELECT * FROM usuarios_suc WHERE sucursal=:sucursal",array(":sucursal"=>$idsuc));
    $puestos_suc=leoDB($pdo,"SELECT * FROM puestos_suc WHERE sucursal=:sucursal",array(":sucursal"=>$idsuc));
    $filas=leoDB($pdo,"SELECT * FROM filas",array());
    $usuarios=leoDB($pdo,"SELECT * FROM usuarios",array());
    $data=array("idsuc"=>$idsuc, "sucursales"=>$suc, "horarios_suc"=>$horarios_suc, "usuarios_suc"=>$usuarios_suc, "puestos_suc"=>$puestos_suc, "usuarios"=>$usuarios,"filas"=>$filas);    
}else if($tipo =="archivos"){
    $suc=leoDB($pdo,"SELECT * FROM sucursales",array());
    $archivos=leoDB($pdo,"SELECT * FROM archivos",array());
    $data=array("sucursales"=>$suc, "archivos"=>$archivos);
}else if($tipo =="agendas"){
    $sucursales=leoDB($pdo,"SELECT * FROM sucursales",array());
    $filas=leoDB($pdo,"SELECT * FROM filas WHERE tipo=:tipo",array(":tipo"=>"Programada"));
    $puestos_suc=leoDB($pdo,"SELECT * FROM puestos_suc",array());
    $horariosagendas=leoDB($pdo,"SELECT h.*,p.* FROM horariosagendas h LEFT JOIN puestos_suc p ON p.id=h.puesto",array());
    $data=array("sucursales"=>$sucursales, "filas"=>$filas, "puestos_suc"=>$puestos_suc, "horariosagendas"=>$horariosagendas);
}else if($tipo =="horariosagendas"){
    $horariosagendas=leoDB($pdo,"SELECT h.*,p.* FROM horariosagendas h LEFT JOIN puestos_suc p ON p.id=h.puesto WHERE h.puesto=:puesto",array(":puesto"=>$_REQUEST["puesto"]));
    $turnosagendados=leoDB($pdo,"SELECT t.*,DAYOFWEEK(fechahora) as dia FROM turnosagendados t WHERE t.puesto=:puesto AND fechahora>NOW()",array(":puesto"=>$_REQUEST["puesto"]));
    $data=array("horariosagendas"=>$horariosagendas, "turnosagendados"=>$turnosagendados);
}else{
    echo json_encode(array("rta"=>"ERROR","msg"=>"Tipo no reconocido"));
    die();
}

$rta=array("tipo"=>$tipo, "data"=>$data);
echo json_encode($rta);

?>
