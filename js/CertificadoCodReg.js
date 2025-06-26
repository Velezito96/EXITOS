let formulario=new FormData(document.getElementById('frmCertificado'));
let numero=document.getElementById("numero").value;

fetch("./consultaCertiCodReg.php",{
    method:"POST",
    body:formulario
})        
    .then(res => res.json())
    .then(data => {
        if(data.length>0){
        
        data.map(item=>{
            document.getElementById('contenedorNombre').value=`${item.apellidosNombres}`;
            document.getElementById('contenedorNombreCurso').innerHTML=`${item.nombreCurso}`;
            let fechaIni=new Date(`${item.fechaInicio}`);
            let fechaInicio=fechaIni.toLocaleDateString("es-ES",{day:"numeric",month:"long",year:"numeric"})
            let fechaFi=new Date(`${item.fechaFin}`);
            let fechaFin=fechaFi.toLocaleDateString("es-ES",{day:"numeric",month:"long",year:"numeric"})
            document.getElementById('contenedorFechaDuracion').innerHTML=`Desarrollado del ${fechaInicio} hasta el ${fechaFin}. Con una duración de ${item.numHoras} horas académicas.`
            let fechaEnt=new Date(`${item.fechaEntrega}`);
            let fechaEntrega=fechaEnt.toLocaleDateString("es-ES",{day:"numeric",month:"long",year:"numeric"})
            document.getElementById('contenedorFechaEntrega').innerHTML=`${fechaEntrega}.`;
            document.getElementById('contenedorCodRegistro').innerHTML=`Codigo. Reg: ${item.codRegistro}`;
            let contenedorQR=document.getElementById('contenedorCodQr')
            new QRCode(contenedorQR,`http://localhost:3000/frmcertificadoCodReg.php?numero=+${item.codRegistro}`)
            document.getElementById('contenedorUrls').innerHTML=`${item.urls}`;
        });

    }else{
        document.getElementById('Certificados').style.display="none";
        //document.getElementById('cabecera').style.display="none";
        document.getElementById('sms').style.display="block";
        document.getElementById('sms').innerHTML=`NO SE ENCONTRÓ RESULTADOS PARA EL CÓDIGO DE REGISTRO: ` + numero + ` INGRESADO`;
    }
});
