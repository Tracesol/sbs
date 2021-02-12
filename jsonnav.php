<?php
    include("pages.php");
    header('Content-Type: application/json; charset=utf-8');

    if(isset($_REQUEST["nivel"])){
        $nivel=$_REQUEST["nivel"];
    }else{
        $nivel="Todos";
    }

    $appsnivel=array();
    foreach($apps as $app){
        if(in_array($nivel,$app["ocultarpara"]))
            $app["menu"]="No";
        if($app["nivel"] == "Todos" || $nivel == "Admin" || $app["nivel"] == $nivel)
            array_push($appsnivel,$app);
    } 
    echo json_encode($appsnivel);        
   
?>