<!DOCTYPE html>
<html lang="es">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="utiles/css/login.css" rel="stylesheet" type="text/css" media="screen"/>
<script src="utiles/js/jquery-1.9.1.min.js" type="text/javascript" ></script>
<script src="utiles/js/envios.js" type="text/javascript" ></script>
<script type="text/javascript">
  
function envio(){ 
 var x = $("#msg");
 x.html ("<p>Cargando...</p>");
 x.show("slow");
  
 var usuario = document.getElementById("username");
 var contraseña = document.getElementById("password");

    if (usuario.value=="" || contraseña.value==""){
      x.html ( "<p>Error: Tiene Campos Vacios</p>");
      setTimeout("$('#msg').hide();", 1000);
    }else{
//esta linea si se moidifica no altera nd? //
        var url="/colegio/inicio/verificarUsuario";
        var data="usuario="+usuario.value+"&clave="+contraseña.value;

        envioJson2(url,data,function respuesta(res){   
        
        if (res == 0){
                
                var div = document.getElementById("msg");
                x.html ( "<p>Usuario o Contraseña Incorrectos</p>");
                usuario.value="";
                usuario.setAttribute("autofocus","true");
                contraseña.value="";
                setTimeout("$('#msg').hide();", 2000);
            }else{
                
                document.location.href=res;
            }
         });
    }   
}
window.onload = function() {
    $('#password').bind('keypress', function (e) {
        var key = e.keyCode || e.which;
        if (key === 13) {
            envio();
        };
    });          
}

</script>
<title><?php echo $titulo; ?></title>
</head>
<body>
    
    <?php 
   /*
    $clave="ilba";
    $clave1= sha1($clave) ;
    echo $clave1;
   */
    ?>
     <div id="cuerpo">
         <div class="escudo"><img style="margin-top: 23%"  width="70%"  src="utiles/imagenes/logo.png" alt="ESCUDO"></div>
         <div class="separador"></div>
         <div id="login">
         <h1>Iniciar Sesion</h1>
            <div id="msg"></div>
            <input name="usuario" id="username" type="text" class="cajaTexto" placeholder="Usuario" required autofocus />
            <input name="contraseña"  id="password" type="password" class="cajaTexto" placeholder="Contraseña" required />
            <button onclick="envio();" class="button blue">Iniciar Sesión </button>
            <a href="/colegio/inicio/olvidoclave" class="olvidoPass"></br>¿Olvidaste tu Contraseña?</a>
            
         </div>
         </div>
   
    </br>
  <!-- <div id="footer">
       <div id="opcionesFooter">
        <li><a href="#">© 2020 Proyecto Integración Continua</a></li>
        <li><a href="#">Terminos</a></li>
        <li><a href="#">Centro de Ayuda</a></li>
        <li><a href="#">Reportar un Error</a></li>
        <li><a href="#">Ayudanos a Mejorar</a></li>  
       </div>    
   </div>-->

</body>
</html>





        