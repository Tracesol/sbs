<?php

	function error2txt($code){
		$message="Unknown upload error";
		switch ($code) {
				case UPLOAD_ERR_INI_SIZE:
						$message = "Archivo demasiado grande. Revisar php.ini";
						break;
				case UPLOAD_ERR_FORM_SIZE:
						$message = "Archivo demasiado grande.";
						break;
				case UPLOAD_ERR_PARTIAL:
						$message = "Archivo subido parcialmente";
						break;
				case UPLOAD_ERR_NO_FILE:
						$message = "Sin archivo a subir";
						break;
				case UPLOAD_ERR_NO_TMP_DIR:
						$message = "Sin carpeta temporaria";
						break;
				case UPLOAD_ERR_CANT_WRITE:
						$message = "Falla al escribir al disco";
						break;
				case UPLOAD_ERR_EXTENSION:
						$message = "Extension no permitida";
						break;

				default:
						$message = "Error desconocido";
						break;
		}
		return $message;
	}

	header('Content-Type: application/json; charset=utf-8');

	$inputname="archivo";
	$target_path = "uploads/";
	if(count($_FILES[$inputname]['name']) < 1)
		die('{"rta":"Error","msg":"Sin archivo que subir."}');

	$ext=explode(".",$_FILES[$inputname]['name']);
	$nombre=date("YmdHis").".".$ext[1];
	$target_path = $target_path . basename( $nombre );

	if(move_uploaded_file($_FILES[$inputname]['tmp_name'], $target_path)) {
		echo json_encode(array("rta"=>"OK", "nombre"=>$target_path));
	} else{
		echo json_encode(array("rta"=>"Error","msg"=>"Error al subir archivo => ".error2txt($_FILES[$inputname]["error"])));
	}

?>
