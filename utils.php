<?php

function fechaMysql($fechahora){
	if($fechahora == "")
		return "";
	$x=explode(" ",$fechahora);
	$f=explode("/",$x[0]);
	$h=explode(":",$x[1]);
	$anio=$f[2];
	$mes=$f[1];
	$dia=$f[0];
	$hora=(((strlen($h[0]) < 2) && (intval($h[0]) < 10) )?("0".$h[0]):($h[0]));
	$min=$h[1];
	$seg=$h[2];	
	return "$anio-$mes-$dia $hora:$min:$seg";

}


function getLocalPage($url){

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 90); //conection timeout in seconds
	curl_setopt($ch, CURLOPT_TIMEOUT, 180); //timeout in seconds

	$output = curl_exec($ch);
	$info = curl_getinfo($ch);
	if(!$output)
		return "HTTP REQUEST ERROR";
	else
		return $output;
}


function postLocalPage($url,$data){

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 90); //conection timeout in seconds
	curl_setopt($ch, CURLOPT_TIMEOUT, 180); //timeout in seconds

	$output = curl_exec($ch);
	$info = curl_getinfo($ch);
	if(!$output)
		return "HTTP REQUEST ERROR";
	else
		return $output;
}






function formatDuracion($duracion){
		switch(true){
			case ($duracion > 86400):
				$duracion=round($duracion/86400, 1)." d&iacute;as";
				break;
			case ($duracion < 86400 && $duracion > 3600 ):
				$duracion=round($duracion/3600,1)." hs.";
				break;
			case ($duracion < 3600 && $duracion > 60):
				$duracion=round($duracion/60,1)." min.";
				break;
			default:
				$duracion = round($duracion, 1). " seg.";
				break;
		}
		return $duracion;
}

?>
