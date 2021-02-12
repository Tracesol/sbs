const cont=document.querySelector("#cont");
isLogged();
isNotifEnabled();
var registration;
var offline=false;

function isLogged(){
    let sessdata=localStorage.getItem("sessdata");
    if(sessdata != null){
        let ls=JSON.parse(sessdata);
        let d=new Date();
        if((d.getTime() - ls.timestamp) < 7*24*3600*1000 ){
            let cad='';
            fetch("jsonnav.php?nivel="+ls.nivel)
            .then((resp)=>{return resp.json();})
            .then((jsonnav)=>{
                let ubicacion_ant=jsonnav[0].ubicacion;
                for(let i in jsonnav){
                    let s=jsonnav[i].nombre;
                    if(jsonnav[i].menu != "No"){
                        if(ubicacion_ant == "" && jsonnav[i].ubicacion == ""){
                            cad+='<li class="nav-item"><a class="nav-link" href="index.html?p='+s.toLowerCase()+'">'+s+'</a></li>';
                        }else if(ubicacion_ant == "" && jsonnav[i].ubicacion != ""){
                            cad+='<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" id="navbarDropdown'+jsonnav[i].ubicacion+'" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+jsonnav[i].ubicacion+'</a>';
                            cad+='<div class="dropdown-menu" aria-labelledby="navbarDropdown'+jsonnav[i].ubicacion+'"><a class="dropdown-item" href="index.html?p='+s.toLowerCase()+'">'+s+'</a>';
                        }else if(ubicacion_ant == jsonnav[i].ubicacion && jsonnav[i].ubicacion != ""){
                            cad+='<a class="dropdown-item" href="index.html?p='+s.toLowerCase()+'">'+s+'</a>';
                        }else if(ubicacion_ant != jsonnav[i].ubicacion && jsonnav[i].ubicacion != ""){
                            cad+='</div></li><li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" id="navbarDropdown'+jsonnav[i].ubicacion+'" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+jsonnav[i].ubicacion+'</a>'
                            cad+='<div class="dropdown-menu" aria-labelledby="navbarDropdown'+jsonnav[i].ubicacion+'"><a class="dropdown-item" href="index.html?p='+s.toLowerCase()+'">'+s+'</a>';
                        }else if(ubicacion_ant != jsonnav[i].ubicacion && jsonnav[i].ubicacion == ""){
                            cad+='</div></li><li class="nav-item"><a class="nav-link" href="index.html?p='+s.toLowerCase()+'">'+s+'</a></li>'
                        }
                        ubicacion_ant=jsonnav[i].ubicacion; 
                    }
                }
                let navbar='';
                if(jsonnav[0].ubicacion == "")
                    navbar += '<ul class="navbar-nav mr-auto">' + cad;
                else{
                    let s=jsonnav[0].nombre;
                    navbar += '<ul class="navbar-nav mr-auto"><li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+jsonnav[0].ubicacion+'</a><div class="dropdown-menu" aria-labelledby="navbarDropdown">' + cad ;
                }

                if(jsonnav[jsonnav.length - 1].ubicacion == "")
                    navbar += '</ul>';
                else
                    navbar += '</div></li></ul>';
                
                
                navbar += '<div class="float-right text-primary" id="logout" onclick="logout()"><span class="fa fa-user "></span>&nbsp;<span id="usuario">--</span></div>';
                document.querySelector("#navbarSupportedContent").innerHTML=navbar;
                document.querySelector("#usuario").innerHTML=ls.nombre;
            });
            viewContent();
            return;
        }else{
              localStorage.removeItem('sessdata');
        }
    }
    fetch("jsonnav.php?nivel=Todos")
    .then((resp)=>{return resp.json();})
    .then((jsonnav)=>{
        let cad='';
        for(let i in jsonnav){
            let s=jsonnav[i].nombre;
            if(jsonnav[i].menu != "No")
                cad+='<li class="nav-item"><a class="nav-link" href="index.html?p='+s.toLowerCase()+'">'+s+'</a></li>';
        }
        let navbar=`<ul class="navbar-nav mr-auto">`+cad+`</ul>
            <div class="float-right text-primary" id="logout" onclick="logout()"><span class="fa fa-user "></span>&nbsp;<span id="usuario">Ingresar</span></div>`;
        document.querySelector("#navbarSupportedContent").innerHTML=navbar;
        viewContent();
    });

}




function viewContent(){
    let objurl=getUrlVars();
    let sessdata=localStorage.getItem('sessdata');
    if(sessdata == null)
        sessdata='{"nivel":"Todos"}';
    if(typeof objurl.p !== "undefined"){
        $(".nav-item").each(function(){
            var u=$(this).find(".nav-link").attr("href").split("=");
            if(objurl.p == u[1])
                $(this).addClass("active");
            else
                $(this).removeClass("active");
        });
    }
    if(typeof objurl.p === "undefined"){
        objurl.p="bienvenida";
    }

    fetch('content.php?content=' + objurl.p + "&sessdata=" + sessdata)
    .then((resp)=>{
        return resp.json();
    })
    .then((json)=>{
        if(json.rta == "OK"){
            $("#cont").empty().off().append(json.msg);
        }else{
            cont.innerHTML='';
            location.assign("index.html");
        }
    })
}


function logout(){
    localStorage.clear();
    location.assign("index.html?p=login");
}


function alerta(titu,msg){
    $("#modaltitu").html(titu);
    $("#modalbody").html(msg);
    $("#modalaceptar").addClass("d-none");
    $("#modal").modal("toggle");
}


function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = decodeURIComponent((value + '').replace(/\+/g, '%20'));
    });
    return vars;
}



$(document).ajaxStart(function () {
    $('#loading').show();
}).ajaxStop(function () {
    $('#loading').hide();
});


$( document ).ajaxError(function( event, request, settings ) {
    if(offline === false){
        alert("Sin acceso a internet. Llamar a la sucursal.");
        offline=true;
    }   
});

$(document).on("focusout",".inputhora",function(e){
    if($(this).val() == "")
        return;

    if($(this).val().length != 5){
        alert("Formato de hora invalido. Usar hh:mm. Ejemplo 13:30 o 07:20");
        $(this).val("").focus();
        return;
    }
    if(!checaHora($(this).val())){
        var o=$(this);
        alert("Hora invalida. Usar hh:mm. Ejemplo 13:30 o 07:20");
        o.val("").focus();
        return;
    }
});

function checaHora(hora){
    var ok=/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/.test(hora);
    if(!ok)
        return false;
    var hh=parseInt(hora.split(":")[0]);
    var mm=parseInt(hora.split(":")[1]);
    if(!Number.isInteger(hh) || !Number.isInteger(mm) ||  hh > 23 || mm > 59 || hh < 0 || mm < 0)
        return false;
    else
        return true;
}


function isNotifEnabled(){
    //veo si guarde el estado de notif. si lo guarde lo respeto. si no lo guardé pregunto y guardo
    navigator.serviceWorker.register('worker.js');

    let notif=localStorage.getItem("notif");
    let permi=Notification.permission;
    if(notif == null){
        if(permi === "granted"){
            notif="granted";
            localStorage.setItem("notif","granted");
            navigator.serviceWorker.ready.then( function( resp ){
                registration=resp;
            });
        }else{
            $("#modaltitle").html("Atención");
            $("#modalbody").html("Habilitar las notificaciones del navegador para recibir avisos.");
            $("#modalaceptar").removeClass("d-none").on("click",function(){
                $("#modal").modal("toggle");
                Notification.requestPermission().then( function( permission ){
                    localStorage.setItem("notif",permission);
                    if ( permission != "granted" ){
                        alert("No recibirá notificaciones. Prestar atención a los mensajes de la pantalla." );
                        return;
                    }
                    
                    navigator.serviceWorker.ready.then( function( resp ){
                        registration=resp;
                    });
                });
            });
            $("#modal").modal("toggle");
        }
    }else{
        if(notif === "granted"){
            navigator.serviceWorker.ready.then( function( resp ){
                registration=resp;
            });
        }
    }
}


function showNotif(title,msg){
    if(Notification.permission === "granted")
        registration.showNotification( title, {
            body:msg,
            badge:"images/logo_150.png",
            vibrate: [300, 100, 400],
            tag:"turnos_SBS"
        } );
    else
        alerta(title, msg);
}
