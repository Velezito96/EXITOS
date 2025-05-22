<?php 
  include('header.php');
?>
<body class="p-3 m-0 border-0 bd-example">
  <form id="formulario" method="post" class="row g-3 needs-validation was-validated" enctype="multipart/form-data">
    <div class="col-md-6">
      <label for="nombreCurso" class="form-label">Nombre del Curso</label>
      <input id="nombreCurso" type="text" class="form-control" name="nombreCurso" required>
    </div>
    <div class="col-md-6">
      <label for="descripcion" class="form-label">Descripción del curso</label>
      <input id="descripcion" type="text" class="form-control" name="descripcion"  required>
    </div>
    <div class="mb-3">
      <input type="file" class="form-control" id="imagen" name="imagen" required>
      <div class="invalid-feedback"> Seleccione un arhivo de tipo imagen (*.jpg, *.png, *.jpeg)
      </div>
    </div>
    
    
    <button type="submit">ENVIAR</button>
    </form>
    <script src="./js/registroCursos.js"></script>
</body>