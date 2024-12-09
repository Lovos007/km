/* Enviar formularios via AJAX */

const formularios_ajax=document.querySelectorAll(".FormularioAjax");



formularios_ajax.forEach(formularios => {

    formularios.addEventListener("submit",function(e){
             
        e.preventDefault();

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Quieres realizar la acción solicitada",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, realizar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed){

                let data = new FormData(this);
                let method=this.getAttribute("method");
                let action=this.getAttribute("action");

                let encabezados= new Headers();

                let config={
                    method: method,
                    headers: encabezados,
                    mode: 'cors',
                    cache: 'no-cache',
                    body: data
                };

                fetch(action,config)
                .then(respuesta => respuesta.json())
                .then(respuesta =>{ 
                    return alertas_ajax(respuesta);
                });
            }
        });

    });

});


function alertas_ajax(alerta) {
    if (alerta.tipo == "simple") {
        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        });
    } else if (alerta.tipo == "recargar") {
        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
            }
        });
    } else if (alerta.tipo == "limpiar") {
        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector(".FormularioAjax").reset();
            }
        });
    } else if (alerta.tipo == "redireccionar") {
        window.location.href = alerta.url;
    } else if (alerta.tipo == "simpleRedireccion") {
        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = alerta.url; // Redirección después de confirmar
            }
        });
    }
}


// Función para codificar en base64 en JavaScript
function base64_encode(str) {
    return btoa(unescape(encodeURIComponent(str)));
}

// Obtener el formulario
var searchForm = document.getElementById('searchForm');

// Verificar si el formulario existe
if (searchForm) {
    // Interceptar el envío del formulario
    searchForm.addEventListener('submit', function(event) {
        var searchField = document.getElementById('search');
        
        // Verificar si el campo de búsqueda existe y tiene valor
        if (searchField) {
            // Encriptar el valor del campo de búsqueda antes de enviarlo
            var encodedSearch = base64_encode(searchField.value);
            
            // Colocar el valor encriptado en el campo oculto
            var hiddenField = document.getElementById('search_encrypted');
            if (hiddenField) {
                hiddenField.value = encodedSearch;
            }
        }
    });
}

function enviarPost(modulo,valor) {
    // Asignar el valor al campo oculto
    document.getElementById('id').value = valor;
    document.getElementById('modulo').value = modulo;
    // Enviar el formulario
    document.getElementById('PostFormData').submit();
}