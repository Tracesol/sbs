<?php
//data via post
//  data[op]=INS, DEL, UPD
//  data[tabla]
//  data[filtro]=eq, neq, gt, lt, etc.
//  data[orden]= tag,driver asc
header('Content-Type: application/json; charset=utf-8');
include("funcionesSQL.php");
$pdo=conectoDB();
$parametros=array();
$escribi=false;
if($_POST["op"] == "ins"){
  $lista=implode(",",array_keys($_POST["parametros"]));
  $lista2=":".implode(",:",array_keys($_POST["parametros"]));
  $parametros=array();
  foreach($_POST["parametros"] as $k=>$v){
    $parametros[":".$k]=$v;
  }
  $sql="INSERT INTO ".$_POST["tabla"]." ($lista) VALUES ($lista2)";
  $escribi=escriboDB($pdo, $sql, $parametros);
}else if($_POST["op"] == "insmul"){
  $lista=implode(",",array_keys($_POST["parametros"][0]));
  $lista2=":".implode(",:",array_keys($_POST["parametros"][0]));
  $sql="INSERT INTO ".$_POST["tabla"]." ($lista) VALUES ($lista2)";
  foreach($_POST["parametros"] as $k=>$v){
    $escribi=escriboDB($pdo, $sql, $v);
  }
}else if($_POST["op"] == "upd"){
  $filtro=$_POST["filtro"];
  $set=$_POST["set"];
  $sql="UPDATE ".$_POST["tabla"]." SET $set WHERE $filtro";
  $parametros=array();
  foreach($_POST["parametros"] as $k=>$v){
    $parametros[":".$k]=$v;
  }
  $escribi=escriboDB($pdo, $sql, $parametros);
}else if($_POST["op"] == "del"){
  $sql="DELETE FROM ".$_POST["tabla"]." WHERE ".$_REQUEST["filtro"];
  $escribi=escriboDB($pdo, $sql, null);
}

if(!$escribi){
//  echo "ERROR. ".$sql."  ".var_export($parametros,true)." escribi:".var_export($escribi,true);
  echo json_encode(array("rta"=>"ERROR","msg"=>var_export($escribi,true),"sql"=>$sql,"req"=>$_POST));
}else{
  if($_POST["op"] == "ins"){
    $result=leoDB($pdo,'SELECT MAX(id) as idmax FROM '.$_POST["tabla"],null);
    echo json_encode(array("rta"=>"OK","msg"=>var_export($escribi,true),"idmax"=>$result[0]["idmax"]));
  }else{
    echo json_encode(array("rta"=>"OK","msg"=>var_export($escribi,true)));
  }
}

cierroDB($pdo);
die();
?>
