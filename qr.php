<?php

include('phpqrcode.php');

if(!isset($_REQUEST['sucursal']))
    die();

$suc=$_REQUEST['sucursal'];

// SVG file format support
QRcode::png('https://www.tracesol.com/sbs/index.html?p=pedidos&sucursal=' . $suc ,false, QR_ECLEVEL_H, 4 ); 


?>