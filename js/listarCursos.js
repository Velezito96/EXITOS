function iniciarScript(){
fetch("./listarCursos.php")
.then(res => res.json())
.then(data => {
    let str="";
    data.map(item=>{
    str+=
        `<div class="col">
        
            <div class="card h-100">
        
                <div class="" style="padding: 0%">
                    <a href="#" class="btn btn-primary btn-modal" data-idcurso="${item.idCurso}"; data-nombreCurso="${item.nombreCurso}" style="text-decoration:none" target="_blank">
                    <img src="./img/fotosCursos/${item.imagen}" class="card-img-top" alt="..."></a>
                </div>
            
                <div class="card-body">
                    <h5 class="card-title align="Center"">${item.nombreCurso}</h5>
                    <p class="card-text">${item.descripcion}</p>
                </div>

                <div class="card-footer text-muted" style="padding: 0%">       
                    <a href="#" class="btn btn-primary btn-modal" data-idcurso="${item.idCurso}"; data-nombrecurso="${item.nombreCurso}">Solicitar información</a>
                </div>
            </div>

        </div>`           
    });
    document.getElementById('contenedorDatos').innerHTML+=str; 
    botones=document.querySelectorAll('.btn-modal');
    botones.forEach(boton => {
        boton.addEventListener('click',function(e){
            e.preventDefault();
            btn=e.target;
            idCurso=btn.dataset.idcurso;
            document.getElementById('contenedorId').innerHTML=idCurso;
            nombrecurso=btn.dataset.nombrecurso;
            document.getElementById('contenedorCurso').innerHTML=nombrecurso;
            modalContent=document.querySelector('#formModal .modal-body');
                const myModal = new bootstrap.Modal(document.getElementById('formModal'),{
                backdrop:true,
                focus:true,
                keyboard:true
            });
            myModal.show();
            })
        });
    })
}
window.addEventListener("load", iniciarScript)