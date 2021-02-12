<?php
include_once("funcionesSQL.php");

$sql="SELECT ";
if(!empty($_REQUEST['campos']))
	$sql.=$_REQUEST['campos'];
else
	$sql.='* ';

if(!empty($_REQUEST['tabla']))
	$sql.=' FROM '.$_REQUEST['tabla'];

if(!empty($_REQUEST['filtro']))
	$sql.=' WHERE '.$_REQUEST['filtro'];

if(!empty($_REQUEST['orderby']))
	$sql.=' ORDER BY '.$_REQUEST['orderby'];

if(!empty($_REQUEST['groupby']))
	$sql.=' GROUP BY '.$_REQUEST['groupby'];

if(!empty($_REQUEST['limit']))
	$sql.=' LIMIT '.$_REQUEST['limit'];


$pdo=conectoDB();
if(!empty($_REQUEST['charset'])){
    $pdo->query("SET CHARACTER SET ".$_REQUEST['charset'].";");
}

$rs = leoDB($pdo,$sql,null);

$arr=array();
foreach($rs as $row){
	$arr2=array();
	foreach($row as $k=>$v){
		if(is_string($v))
			$arr2[$k]=$v;
		else
			$arr2[$k]=$v;
	}
	array_push($arr,$arr2);
}

header('Content-Type: application/json; charset=utf-8');


echo json_encode($arr);

cierroDB($pdo);

?>
