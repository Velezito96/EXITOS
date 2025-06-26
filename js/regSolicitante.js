function iniciarScript(){
    const frm=document.querySelector('#formulario-solicitante');
    frm.addEventListener('submit',(event)=>{
        event.preventDefault();

        let idCurso= document.getElementById("contenedorId").innerHTML;
        alert(idCurso)
        
        let padre=document.querySelector('#formulario-solicitante');
        var input=document.createElement("input");
        let formulario=new FormData(document.getElementById('formulario-solicitante'));
        fetch("./regSolicitante.php",{
            method:"POST",
            body:formulario
            
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById("numeroDNI").value='';
            document.getElementById("apellidosNombres").value='';
            document.getElementById("telefono").value='';
            document.getElementById("Email").value='';
            alert("Los datos se registraron correctamente, en un momento nos contactaremos con usted.")
        });
    });
}
window.addEventListener("load", iniciarScript)