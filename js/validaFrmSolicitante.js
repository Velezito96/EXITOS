function iniciarScript(){
const formularioSolicitante=document.getElementById("formulario-solicitante");
const inputs=document.querySelectorAll('#formulario-solicitante input');
let seleccionIdentificacion=document.getElementById('grupo_tipoIdentificacion');
let seleccionTipoCliente=document.querySelector('#grupo_tipoCliente');
let elementos=["DNI","RUC","Pasaporte","Carnet de Extranjería"];
let str="";
	const expresiones = {
		numeroDNI:  /^\d{8,12}$/,
		apellidosNombres: /^[a-zA-ZÀ-ÿ\s]{1,40}$/,
		telefono: /^\d{9}$/,
		email: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/	
	}
	seleccionTipoCliente.addEventListener('change',(event)=>{
		if(document.getElementById("grupo_tipoCliente").value==2){
			str="";
			document.getElementById('grupo_tipoIdentificacion').innerHTML=str;
			numeroDNI.setAttribute("placeHolder","N°. RUC");
			str+=`<option value="2">RUC</option>`;	
			document.getElementById('grupo_tipoIdentificacion').innerHTML=str;
		}else{
			numeroDNI.setAttribute("placeHolder","N°. DNI");
			str=""
			for(let i=0;i<elementos.length; i++){
				str+=`<option value="${[i+1]}">${elementos[i]}</option>`	
			}
			document.getElementById('grupo_tipoIdentificacion').innerHTML=str
		}
	});
	const campos={
		numeroDNI:false,
		apellidosNombres:false,
		telefono:false,
		email:false
	}
	function claseX(){
		document.getElementById(`grupo_numeroDNI`).style.border='3px solid #f30b0b';
		document.querySelector(`#grupo_numeroDNI a`).classList.remove('input-icon-check')
		document.querySelector(`#grupo_numeroDNI a`).classList.add('input-icon-x');
		document.querySelector(`#grupo_numeroDNI a`).classList.remove('bi-check-circle-fill');
		document.querySelector(`#grupo_numeroDNI a`).classList.add('bi-x-circle-fill');
		campos.numeroDNI=false
	}
	function validarlongitud(){
		if(document.getElementById("grupo_tipoIdentificacion").value == 1 && document.getElementById("numeroDNI").value.length == 8){
			buscarCliente();
			validarDatos(expresiones.numeroDNI,numeroDNI,"numeroDNI");
		}else if(document.getElementById("grupo_tipoIdentificacion").value == 2 && document.getElementById("numeroDNI").value.length == 11){
			buscarCliente();
			validarDatos(expresiones.numeroDNI,numeroDNI,"numeroDNI");
		}else if((document.getElementById("grupo_tipoIdentificacion").value == 3 || document.getElementById("grupo_tipoIdentificacion").value == 4) && document.getElementById("numeroDNI").value.length == 12){
			buscarCliente();
			validarDatos(expresiones.numeroDNI,numeroDNI,"numeroDNI");
		}else{
			claseX();
			inputsVacios();
			estadoIconos();
		}
	}
	function inputsVacios(){
		document.getElementById(`apellidosNombres`).value="";
		document.getElementById(`telefono`).value="";
		document.getElementById(`email`).value="";
	}
	function estadoIconos(){
		validarDatos(expresiones.apellidosNombres,apellidosNombres,"apellidosNombres");
		validarDatos(expresiones.telefono,telefono,"telefono");
		validarDatos(expresiones.email,email,"email");
	}
	function buscarCliente(){
		let formulario=new FormData(document.getElementById('formulario-solicitante'));
		fetch("./buscarCliente.php",{
			method:"POST",
			body:formulario
		})
			.then(res => res.json())
			.then(data => {
				$fila=data.length
				if($fila==1){
					data.map(item=>{
						document.getElementById('apellidosNombres').value=`${item.apellidosNombres}`
						document.getElementById('telefono').value=`${item.telefono}`
						document.getElementById('email').value=`${item.email}`
						estadoIconos()
					});
				}else{
					inputsVacios();
					estadoIconos()
				}
		});
	}
	numeroDNI.addEventListener('blur',(event)=>{
		validarlongitud();
	})
	seleccionIdentificacion.addEventListener('change',(event)=>{
		if(seleccionIdentificacion.value==1){
			numeroDNI.value=""
			numeroDNI.setAttribute("placeHolder","N°. DNI");
		}else if(seleccionIdentificacion.value==2){
			numeroDNI.value=""
			numeroDNI.setAttribute("placeHolder","N°. RUC");
		}else if(seleccionIdentificacion.value==3){
			numeroDNI.value=""
			numeroDNI.setAttribute("placeHolder","N°. Pasaporte");
		}else if(seleccionIdentificacion.value==4 ) {
			numeroDNI.value=""
			numeroDNI.setAttribute("placeHolder","N°. Carnet de extranjería");
		}
	});
	const validarFormulario = (e) => {
		switch(e.target.name){
			case "apellidosNombres":
				validarDatos(expresiones.apellidosNombres,e.target,"apellidosNombres");
				break;
			case "telefono":
				validarDatos(expresiones.telefono,e.target,"telefono");
				break;
			case "email":
				validarDatos(expresiones.email,e.target,"email");
				break;
		}
	};
	const validarDatos = (expresion, input, campo) => {
		if(expresion.test(input.value)){
			document.getElementById(`grupo_${campo}`).style.border='3px solid #119200';
			document.querySelector(`#grupo_${campo} a`).classList.remove('input-icon-x');
			document.querySelector(`#grupo_${campo} a`).classList.add('input-icon-check');
			document.querySelector(`#grupo_${campo} a`).classList.remove('bi-x-circle-fill');
			document.querySelector(`#grupo_${campo} a`).classList.add('bi-check-circle-fill');
			campos[campo]=true;
		}else{
			document.getElementById(`grupo_${campo}`).style.border='3px solid #f30b0b';
			document.getElementById(`grupo_${campo}`).classList.remove('input-icon-check');
			document.querySelector(`#grupo_${campo} a`).classList.add('input-icon-x');
			document.querySelector(`#grupo_${campo} a`).classList.remove('bi-check-circle-fill');
			document.querySelector(`#grupo_${campo} a`).classList.add('bi-x-circle-fill');
			campos[campo]=false;
		}
	}
	inputs.forEach((input)=>{
		input.addEventListener('change', validarFormulario);
		input.addEventListener('keyup', validarFormulario);
	});
	formularioSolicitante.addEventListener('submit', (e) => {
		e.preventDefault();
		if(campos.numeroDNI && campos.apellidosNombres && campos.telefono && campos.email ){
			campos.numeroDNI=false
			campos.apellidosNombres=false
			campos.telefono=false
			campos.email=false
			registrarCliente();
		}else{
			alert("Error, Falta registrar algunos campos.")
		}
	});
	function registrarCliente(){
		let idCurso= document.getElementById("contenedorId").innerHTML;
		document.getElementById(`idCurso`).value=idCurso;
		let formulario=new FormData(document.getElementById('formulario-solicitante'));
		fetch("./regSolicitante.php",{
			method:"POST",
			body:formulario
		})
		.then(res => res.json())
		.then(data => {
			alert("Los datos se registraron correctamente, en un momento nos contactaremos con usted.")
			formularioSolicitante.reset()
			document.querySelectorAll('.input-icon-check').forEach((icono) => {
				icono.classList.remove('input-icon-check');
				icono.classList.remove('bi-check-circle-fill');
				document.getElementById(`grupo_numeroDNI`).style.border='#e9ecef';
				document.getElementById(`grupo_apellidosNombres`).style.border='#e9ecef';
				document.getElementById(`grupo_telefono`).style.border='#e9ecef';
				document.getElementById(`grupo_email`).style.border='#e9ecef';
			});
		});
	}
	}
window.addEventListener("load", iniciarScript)