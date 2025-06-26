function iniciarScript(){
const form=document.querySelector('#frmCertificadoIndividual');
form.addEventListener('submit',(event)=>{
    event.preventDefault();
    if(document.querySelector('input[name="consultaOpciones"]:checked').value == "1" && document.getElementById("DNIs").value.length == 8 ) {
        let DNIs=document.getElementById("DNIs").value;
        alert("DNI")
        window.open("certificadoDNI.php?DNI="+DNIs);
    }else if(document.querySelector('input[name="consultaOpciones"]:checked').value == "2" && document.getElementById("DNIs").value.length == 8){
        alert("RUC")
        let DNIs=document.getElementById("DNIs").value;
        window.open("frmcertificadoCodReg.php?DNI="+DNIs);
    }else{
        alert("No se encontraron resultados con la búsqueda realizada. Vuelva a intentar.")
    }  
});
}
window.addEventListener("load", iniciarScript)