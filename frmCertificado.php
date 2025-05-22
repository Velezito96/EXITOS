<body>
  <div class="modal fade" id="frm_M_consulta" tabindex="-1" aria-labelledby="formModal1Label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class= "card-title fs-5 text-center" id="formModal1Label">
            Consultar Certificado 
          </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="frmCertificado">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="consultaOpciones" id="consultaDNI" value="1" checked>
                <label class="form-check-label" for="consultaDNI">DNI</label >
              </div>
              <div class="form-check form-check-inline">
                <input  class="form-check-input" type="radio" name="consultaOpciones" id="consultaRegistro" value="2">
                <label class="form-check-label" for="consultaRegistro">Num. Registro</label>
              </div>
              <div class="input-group flex-nowrap mb-3">
                <input  type="text" name="numero" class="form-control" id="numero">
              </div>
            <div class="modal-footer">
              <button type="submit">Consultar Certificado</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
<script src="js/certificado.js"></script> 