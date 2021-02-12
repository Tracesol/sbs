<?php
function conectoDB(){
	try {
		$db="sbs";
		$host="localhost";
		$user="usuario";
		$pass="usuar10";
		$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
		$pdo->exec("SET CHARACTER SET utf8");

	}catch(PDOException $e) {
		echo ' SQL PDO ERROR: ' . $e->getMessage();
		die();
	}
	return $pdo;
}



function getTipoDato($value){
	switch (true) {
		case is_bool($value):
			$var_type = PDO::PARAM_BOOL;
			break;
		case is_int($value):
			$var_type = PDO::PARAM_INT;
			break;
		case is_null($value):
			$var_type = PDO::PARAM_NULL;
			break;
		default:
			$var_type = PDO::PARAM_STR;
	}
	return $var_type;
}



/*  leoDB()
	Sin Parametro
  	$pdo_vtiger=conectoDB("vtiger");
	$cad=leoDB($pdo, "SELECT id, username from tabla where id < 10");
	foreach($cad as $row){
		echo $row['id'].": ".$row['username']."<br>";
	}

	Con parametros
	$par[":id"]=199;
	$cad=leoDB($pdo_vtiger, "SELECT id, user_name from vtiger_users where id = :id",$par);
 */

function leoDB($pdo,$sql,$lista_param){
	try {
		if(isset($lista_param)){
			$stmt=$pdo->prepare($sql);
			foreach($lista_param as $label=>$param){
				$tipo=getTipoDato($param);
				$stmt->bindValue($label, $param,$tipo);
			}
			$stmt->execute();
		}else
			$stmt=$pdo->query($sql);
		$results_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
	}catch(PDOException $e) {
		echo ' SQL PDO ERROR: ' . $e->getMessage();
		die();
	}
	return $results_array;
}




/*  leoDBDato()
	Sin Parametro
  	$pdo_vtiger=conectoDB("vtiger");
	$id=leoDBDato($pdo, "SELECT id from tabla ORDER BY id DESC LIMIT 1");
 */

function leoDBDato($pdo,$sql){
	try {
		$stmt=$pdo->query($sql);
		$results_array=$stmt->fetchAll(PDO::FETCH_ASSOC);
		$resultado=$results_array[0];
	}catch(PDOException $e) {
		echo ' SQL PDO ERROR: ' . $e->getMessage();
		die();
	}
	if($resultado != null){
		$keys = array_keys($resultado);
		return $resultado[$keys[0]];
	}else
		return "";
}





/*	escriboDB()
    Ejemplo	de uso:
	$pdo_vtiger=conectoDB("vtiger");
	$sql="UPDATE vtiger_users SET phone_work=:phone_work WHERE id=:id";
	$param=array();
	$param[":phone_work"]="4493322";
	$param[":id"]=1;
	$ok=escriboDB($pdo_vtiger,$sql,$param);
*/

function escriboDB($pdo,$sql,$lista_param){
	$escribi=0;
	try {
		$stmt=$pdo->prepare($sql);
		if(isset($lista_param)){
			foreach($lista_param as $label=>$param){
				if(substr($label,0,1) != ":")
					$label=":".$label;
				$tipo=getTipoDato($param);
				$stmt->bindValue($label, $param,$tipo);
			}
		}
		$stmt->execute();
		$escribi=$stmt->errorInfo();
	}catch(PDOException $e) {
		echo ' SQL PDO ERROR: ' . $e->getMessage();
		die();
	}

	if($escribi > 0)
		return $escribi;
	else
		return false;

}



function insertMultipleDB($pdo,$sql,$array_param){
	$escribi=0;
	try {
		$pdo->beginTransaction();
		$stmt=$pdo->prepare($sql);
		for($i=0;$i<count($array_param);$i++) {
			$stmt->execute($array_param[$i]);
		}
		$pdo->commit();
		$escribi=$stmt->rowCount();
	}catch(PDOException $e) {
		echo ' SQL PDO ERROR: ' . $e->getMessage();
		die();
	}

	if($escribi > 0)
		return true;
	else
		return false;

}




function cierroDB($pdo){
	$pdo=null;
}

?>
