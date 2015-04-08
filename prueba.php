<?php
include_once("./funciones.php");
?>

<html>
<head>
    
    <title>Kiosco</title>
    <script type="text/javascript" src="./jquery-1.8.1.js"></script>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body id="nomcli">

     
        <form action="tamano_foto.php" method="POST" id="form1">
           <div id="pagina">
            <input type="text" name="pagina" id="txtPaginacion"/><br/><br/>
           <div><input type="submit" id="Enviar" value="Enviar"/></div>
           </div>
        </form>
  

<script type="text/javascript">
            $(document).ready(function(){
               
               $("#form1").submit(function () {
                    if($("#txtPaginacion").val().length < 1) {
                        return false;
                    }
                    return true;
                });
              
            
                $('#txtPaginacion').keyup(function() {
                if (this.value.match(/[^0-9]/g)) {
                this.value = this.value.replace(/[^0-9]/g, '');
                }
                });  
                
                 $("#txtPaginacion").keypress(function(event){
                    if (event.which == 13) {
                    var valor = document.getElementById("txtPaginacion").value;
                    alert(valor);
                   // var totalPaginas = getNumeroDePaginas();
                   // alert('El total de paginas es: '.totalPaginas);
                    }
                });
                
                
            });   
        </script>



    </body>
</html>
