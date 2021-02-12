<?php
/*
    Páginas del sistema.
    nombre: Nombre que aparecerá en el menú
    nivel: Nivel jerárquico  (Todos: público, Sucursal o Admin)
    html: url de la página a mostrar
    menu: visibilidad en el menú de navegación

*/



$apps=array(
    array("nombre"=>"Pedidos","nivel"=>"Todos","html"=>"pedidos.html","menu"=>"Si","ubicacion"=>"","ocultarpara"=>["Admin","Sucursal"]),
    array("nombre"=>"Espera","nivel"=>"Todos","html"=>"espera.html","menu"=>"No","ubicacion"=>"","ocultarpara"=>["Admin","Sucursal"]),
    array("nombre"=>"Bienvenida","nivel"=>"Todos","html"=>"bienvenidos.html","menu"=>"No","ubicacion"=>"Sucursal","ocultarpara"=>[]),
    array("nombre"=>"Login","nivel"=>"Todos","html"=>"login.html","menu"=>"No","ubicacion"=>"Sucursal","ocultarpara"=>[]),
    array("nombre"=>"Llamado","nivel"=>"Sucursal","html"=>"llamado.html","menu"=>"Si","ubicacion"=>"","ocultarpara"=>["Admin"]),
    array("nombre"=>"Sucursales","nivel"=>"Sucursal","html"=>"sucursales.html","menu"=>"Si","ubicacion"=>"Sucursal","ocultarpara"=>[]),
    array("nombre"=>"Horarios","nivel"=>"Sucursal","html"=>"horarios.html","menu"=>"Si","ubicacion"=>"Sucursal","ocultarpara"=>[]),
    array("nombre"=>"Atención","nivel"=>"Sucursal","html"=>"atencion.html","menu"=>"Si","ubicacion"=>"Sucursal","ocultarpara"=>[]),
    array("nombre"=>"QR","nivel"=>"Sucursal","html"=>"qr.html","menu"=>"Si","ubicacion"=>"Sucursal","ocultarpara"=>[]),
    array("nombre"=>"Filas","nivel"=>"Admin","html"=>"filas.html","menu"=>"Si","ubicacion"=>"General","ocultarpara"=>[]),
    array("nombre"=>"Usuarios","nivel"=>"Admin","html"=>"usuarios.html","menu"=>"Si","ubicacion"=>"General","ocultarpara"=>[]),
    array("nombre"=>"Publicidad","nivel"=>"Admin","html"=>"publi.html","menu"=>"Si","ubicacion"=>"General","ocultarpara"=>[]),
    array("nombre"=>"Panel","nivel"=>"Admin","html"=>"panel.html","menu"=>"Si","ubicacion"=>"General","ocultarpara"=>[]),
    array("nombre"=>"Históricos","nivel"=>"Admin","html"=>"historicos.html","menu"=>"Si","ubicacion"=>"General","ocultarpara"=>[]),
    array("nombre"=>"Agendas","nivel"=>"Admin","html"=>"agendas.html","menu"=>"No","ubicacion"=>"Programados","ocultarpara"=>[]),
    array("nombre"=>"At_agendas","nivel"=>"Admin","html"=>"horariosagendas.html","menu"=>"No","ubicacion"=>"Programados","ocultarpara"=>[])
);
?>
