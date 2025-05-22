<?php 
    include("./header.php");  
?>
<body>
  <div class="color card text-white container border text-center" style="padding:0;">
    <form id="frmCertificado" method="post" >
      <div class="card-img-overlay">
        <div class="mb-3">
          <h5 class="card-titulo">Resultado de búsqueda de Certificados y Constancias</h5>
        </div>

        <div>
          <input type="text" style="display:none;" name="numero" class="form-control" id="numero" value= "<?php echo $_GET['numero']?>"> 
        </div>
        
      <section class="margin row g-3" id="datos">
        <div class="col-md-6">
          <label for="inputCity" class="color form-label">Apellidos y Nombres</label>
          <div type="text" class="form-control" id="nombre"></div>
        </div>
        <div class="col-md-4">
          <label for="inputState" class="color form-label" >Tipo de Documento</label>
          <div type="text" class="form-control" id="tipoIdentificacion"></div>
        </div>
        <div class="col-md-2">
          <label for="inputZip" class="color form-label">N°. de documento</label>
          <div type="text" class="form-control" id="numeros"></div>
        </div>
      </section>

      <section class="margin">
        <div style="display: none;" type="text" class="form-control" id="sms"></div>
      </section>

            <section>
            <table class="table">
            
  <thead id="cabecera">
  <th scope="col">Nombre del curso</th>
  <th scope="col">Fecha de Inicio</th>
  <th scope="col">fecha de Fin</th>
  <th scope="col">Nota</th>
  <th scope="col">N° horas</th>
  <th scope="col">Código</th>
  <th scope="col">Seleccione certificados</th>
    
  </thead>
  
  <tbody id="detalleCertificado">

    
  </tbody>
</table>
</section>

      </div>
    </form>
  </div>
</body>
<script src="./js/certificadoDNI.js"></script>