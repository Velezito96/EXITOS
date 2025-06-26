
function iniciarScript(){
    const form=document.querySelector('#frmCertificado');
    form.addEventListener('submit',(event)=>{
        event.preventDefault();
        if(document.querySelector('input[name="consultaOpciones"]:checked').value == "1" && document.getElementById("numero").value.length == 8 ){
            let numero=document.getElementById("numero").value;
            window.open("frmCertificadoDNI.php?numero="+numero);
        }else if(document.querySelector('input[name="consultaOpciones"]:checked').value == "2" && document.getElementById("numero").value.length == 8){
            let numero=document.getElementById("numero").value;
            window.open("frmcertificadoCodReg.php?numero="+numero);
        }else{
        }  
    });
}
window.addEventListener("load", iniciarScript)