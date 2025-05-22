<body>
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class= "card-title fs-5 text-center" id="exampleModalLabel">
          Solicitar Información
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formulario-solicitante" class="formulario-solicitante" method="post">
          <div class="card-title mb-3" id="contenedorCurso" name="contenedorCurso"></div>
          <div style="display: none;" class="card-title mb-3" id="contenedorId" name="contenedorId"></div>
          <label class="label">
            <div class="col-md-12">
              <select name="tipoCliente" class="margin-lista form-select" id="grupo_tipoCliente" required></i>
                <option selected disabled value="">Tipo de cliente</option>
                <option value="1">Persona Natural</option>
                <option value="2">Persona Jurídica</option>
              </select>
            </div>
          </label>
          <div class="input-group col-md-12">
            <select name="tipoIdentificacion" id="grupo_tipoIdentificacion" class="margin-lista form-select" required></select>
          </div>
          <label class="label">
            <div class="input-group flex-nowrap mb-3" id="grupo_numeroDNI">
              <span  class="input-group-text" id="addon-wrapping"><i class="bi bi-credit-card-fill"></i></span>
              <div class="form-input" id="grupo_numeroDNI">
                <input type="text" name="numeroDNI"  id="numeroDNI" class="form-control" placeHolder="N°. DNI">
                <a class="input-icon-check"></a>
              </div>
            </div>
          </label>
          <label class="label">
            <div class="input-group flex-nowrap mb-3" id="grupo_apellidosNombres">
              <span class="input-group-text" id="addon-wrapping"><i class="bi bi-person-fill"></i></span>
              <div class="form-input" id="grupo_apellidosNombres">
                <input type="text" name="apellidosNombres" class="form-control" id="apellidosNombres" placeHolder="Apellidos y Nombres">
                <a class="input-icon-check"></a>
              </div>
            </div>
          </label>
          <label class="label">
            <div class="input-group flex-nowrap mb-3" id="grupo_telefono">
              <span class="input-group-text" id="addon-wrapping"><i class="bi bi-telephone-fill"></i></span>
              <div class="form-input" id="grupo_telefono">
                <input type="number" class="form-control" class="formulario_input" name="telefono" id="telefono" placeholder="N°. Telefono">
                <a class="input-icon-check"></a>
              </div>
            </div>
          </label>
          <label class="label">
            <div class="input-group flex-nowrap mb-3" id="grupo_email">
              <span class="input-group-text" id="addon-wrapping"><i class="bi bi-envelope-at-fill"></i></i></span>
              <div class="form-input" id="grupo_email">
                <input type="email" name="email" class="form-control" id="email" placeHolder="email">
                <a class="input-icon-check"></a>
              </div>
              
            </div>
          </label>
          <div>
          <input type="text" name="idCurso" class="form-control" id="idCurso" placeHolder="idCurso">
          </div>
          <div class="modal-footer">
            <button type="submit">Solicitar Información</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
<script src="./js/validaFrmSolicitante.js"></script>