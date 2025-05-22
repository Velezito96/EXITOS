<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <title>EXITOS DEL SABER</title>
    <link rel="stylesheet" href="./BOOTSTRAP/css/estilos.css">
    <link rel="stylesheet" href="./BOOTSTRAP/css/bootstrap.min.css">
    <link rel="stylesheet" href="./BOOTSTRAP/css/docs.css">    
    <link rel="stylesheet" href="./BOOTSTRAP/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./BOOTSTRAP/fontawesome/fontawesome/css/all.css">
</head>
<body>

<?php
  $numero= $_POST["numero"] . "<br>n";
  $nombre= $_POST["contenedorNombre"] . "<br>n";
?>

<form id="frmCertificado" method="post" action="./CertificadoPrint.php">
    
      <div class="card-img-overlay">
        <section class="cabecera-certificado">
        
          <div>
            <img src="/img/logos/logoCertificados.png"  class="card-img-top" alt="..." >
          </div>
          
          
        </section>
          <div>
          <h5 class="card-titulo">CERTIFICADO</h5>
          </div>
          <div>
          <h3 class="letras-certificado">Otorgado A:</h3> 
          </div>
          <div>
            <input type="text" style="display:block;" name="numero" class="form-control" id="numero" value= "<?php echo $_POST["numero"]?>"> 
          </div>

          <div>
            <input class="letras-nombre text-uppercase" name="contenedorNombre" id="contenedorNombre" value="<?php echo $nombre?>"></input>
          </div>
          </div>
      </div>
      
    </form>

<!--<form id="frmCertificado" method="post" action="./CertificadoPrint.php">
    
      <div class="card-img-overlay">
        <section class="cabecera-certificado">
        
          <div>
            <img src="/img/logos/logoCertificados.png"  class="card-img-top" alt="..." >
          </div>
          
          
        </section>
          <div>
          <h5 class="card-titulo">CERTIFICADO</h5>
          </div>
          <div>
          <h3 class="letras-certificado">Otorgado A:</h3> 
          </div>
          <div>
            <input type="text" style="display:block;" name="numero" class="form-control" id="numero" value= "<?php echo $_GET['numero']?>"> 
          </div>

          <div>
            <input class="letras-nombre text-uppercase" name="contenedorNombre" id="contenedorNombre"></input>
          </div>
          <div>
            <p class="letras-cuerpo">Por haber culminado y aprobado satisfactoriamente todos los módulos del curso de:</p>
          </div>
          <div>
            <div class="letras-nombre text-uppercase" id="contenedorNombreCurso"></div>
          </div>
          <div>
            <div class="letras-cuerpo" id="contenedorFechaDuracion"></div>
          </div>
          <div>
            <div class="letras-cuerpo" id="contenedorHoras"></div>
          </div>
          <div>
            <div class="letras-cuerpo derecha" id="contenedorFechaEntrega">
          </div>
          </div>
          
          
            <div class="cajaQr">
              <div>
                <div class="izquierda" id="contenedorCodQr"></div>
              </div>
              <div>
                <div class="letras-cuerpo" id="contenedorCodRegistro"></div>
              </div>
            </div>
          <div>
            <div class="letras-certificado" id="contenedorUrls"></div>
          </div>
          <div>
      <input type="submit"></input>        
      </div>
      </div>
      
    </form>
  </div>
  
</body>
</html>

<script src="./BOOTSTRAP/js/qrcode.min.js"></script>
<script src="./js/certificadoCodReg.js"></script>
<?php
/*$html=ob_get_clean();
//echo $html;
require_once './pdf/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$options=$dompdf->getOptions();
$options->set(array('isRemoteEnabled'=>true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('letter'); //vertical
//$dompdf->setPaper('A4','landscape'); //horizontal
$dompdf->render();
$dompdf->stream("archivo_.pdf",array("attachment"=>true));*/


?>





</body>
</html>