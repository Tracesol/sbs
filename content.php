<?php
header('Content-Type: application/json; charset=utf-8');


if(!isset($_REQUEST["content"])){
  $html=file_get_contents("bienvenidos.html");
  echo json_encode(array("rta"=>"OK","msg"=>$html));
  die();
}

$sessdata=json_decode($_REQUEST["sessdata"],true);
$content=$_REQUEST["content"];

include("pages.php");
$app=array();
foreach($apps as $k=>$v){
  if(strtolower($v["nombre"]) == $content)
    $app=$v;
}


if($sessdata["nivel"] != "Admin"){
  if($app["nivel"] == "Admin"){
    echo '{"rta":"ERROR","msg":"Sin privilegios para ver esta pagina"}';
    die();
  }
}

if(empty($app))
  echo '{"rta":"ERROR","msg":"Pagina no encontrada"}';
else{
  $html=file_get_contents($app["html"]);
  echo json_encode(array("rta"=>"OK","msg"=>$html));
}

?>