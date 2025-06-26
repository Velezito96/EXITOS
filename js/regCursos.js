function iniciarScript(){
const form=document.querySelector('#formulario');
form.addEventListener('submit',(event)=>{
    event.preventDefault();

    var archivo=document.getElementById('imagen');
    var tipoArchivo=archivo.value;
    var tipoPermitidos=/(.JPG|.jpg|.PNG|.png|.JPEG|.jpeg)$/i;

    if(!tipoPermitidos.exec(tipoArchivo)){
      alert('Tipo de archivo no válido. Solo se permiten imagenes de tipo *.jpg, *.png, *.jpeg');
      archivo.value="";
      return false;
    }else{


//document.getElementById("boton-insertar").addEventListener("click", function(e){
    
    
    let formulario=new FormData(document.getElementById('formulario'));
    
    fetch("./regCurso.php",{
        method:"POST",
        body:formulario
    })
    .then(res => res.json())
    .then(data => {
        //console.log(data)
        //if(data=="true"){
          //  if (isset(nombre)){
            //    alert ("falta datos")
                
              //  }
            document.getElementById("nombre").value='';
            document.getElementById("descripcion").value='';
            document.getElementById("imagen").value='';
            alert("Los datos se registraton correctamente.")
            
        //}else{
          //  console.log(data);
        //}
    });
  }
});

}


window.addEventListener("load", iniciarScript)