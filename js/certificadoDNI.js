function iniciarScript(){
    numero=document.getElementById('numero').value;
let formularioCliente=new FormData(document.getElementById('frmCertificado'));  
fetch("./consultaCertiDNI.php",{
    method:"POST",
    body:formularioCliente
})        
    .then(res => res.json())
    .then(data => {
        if(data.length>0){
            data.map(item=>{
                document.getElementById('nombre').innerHTML=`${item.apellidosNombres}`;
                document.getElementById('tipoIdentificacion').innerHTML=`${item.tipoIdentificacion}`;
                document.getElementById('numeros').innerHTML=`${item.numero}`;
            });
        }else{
            document.getElementById('datos').style.display="none";
            document.getElementById('cabecera').style.display="none";
            document.getElementById('sms').style.display="block";
            document.getElementById('sms').innerHTML=`NO SE ENCONTRÓ RESULTADOS PARA EL DNI ` + numero + ` INGRESADO`;
        }
        
});

let formularioDetalle=new FormData(document.getElementById('frmCertificado'));  
fetch("./consultaCertiDNIDetalle.php",{
    method:"POST",
    body:formularioDetalle
})        
    .then(res => res.json())
    .then(data => {
        data.map(item=>{
            
            str=  
                `<tr>
                    <td>${item.nombreCurso}</td>
                    <td>${item.fechaInicio}</td>
                    <td>${item.fechaFin}</td>
                    <td>${item.nota}</td>
                    <td>${item.numHoras}</td>
                    <td>${item.codRegistro}</td>
                    <td><input type='checkbox' id='cbox1' value=''></td>
                </tr>`
                document.getElementById('detalleCertificado').innerHTML+=str;
        });
});




            /*str=  
                `<tr>
                    <td>${item.nombreCurso}</td>
                    <td>${item.fechaInicio}</td>
                    <td>${item.fechaFin}</td>
                    <td>${item.nota}</td>
                    <td>${item.numHoras}</td>
                    <td>${item.codigoRegistro}</td>
                    <?php echo "<td><input type='checkbox' id='cbox1' value='en ruta'> <br></td>"; ?>
                </tr>`
                document.getElementById('detalleCertificado').innerHTML+=str;*/
            
            /*str=`${item.nombres} ${item.apellidos}`
                document.getElementById('nombres-apellidos').innerHTML=str;
    
            str=`${item.tipoDocumento}`
                console.log(str)      
                document.getElementById('tipo-documento').innerHTML=str;
    
            str=`${item.numeroDNI}`
                document.getElementById('numero-documento').innerHTML=str;
        });
    })    */
    }
    
    window.addEventListener("load", iniciarScript)