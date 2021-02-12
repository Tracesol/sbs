<?php
header('Content-Type: application/json; charset=utf-8');

if(isset($_REQUEST['usr']) && isset($_REQUEST['pwd'] )){

	include("funcionesSQL.php");

	$pdo=conectoDB();

	$usr=$_REQUEST['usr'];
	$pwd=md5($_REQUEST['pwd']);

	$sql="SELECT u.*,p.sucursal  FROM usuarios u LEFT JOIN puestos_suc p ON p.usuario=u.id WHERE u.usuario=:usuario AND u.password=:password";
	$result=leoDB($pdo,$sql,array(":usuario"=>$usr , ":password"=>$pwd));
	$row=$result[0];
	$idusuario=$row["id"];
	$nombre=$row["nombre"];
	$nivel=$row["nivel"];
	$email=$row["email"];
	$sucursal=$row["sucursal"];

	if($idusuario != ""){
		echo json_encode(array("rta"=>"OK","data"=>array("id"=>$idusuario,"nombre"=>$nombre,"nivel"=>$nivel,"email"=>$email,"sucursal"=>$sucursal)));
	}else{
		echo json_encode(array("rta"=>"ERROR","msg"=>"NOTFOUND"));
	}
	cierroDB($pdo);
}else
	echo json_encode(array("rta"=>"ERROR","msg"=>"BADDATA"));

die();
?>
