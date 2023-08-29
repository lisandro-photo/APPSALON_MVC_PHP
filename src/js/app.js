let paso = 1; // indica que paso se mostrará al iniciar la App de cita
const pasoInicial = 1; // const porque serán valores constantes, es decir, que no cambiarán sus valores nunca. De lo contrario serían un let
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}
document.addEventListener('DOMContentLoaded', function () {
    iniciarApp();
});

function iniciarApp() {
    mostrarSeccion(); // Muestra y oculta las secciones
    tabs(); // Cambia la sección cuando se presionen los tabs
    botonesPaginador(); // Agrega o quita los botones del paginador (Anterior - Siguiente)
    paginaAnterior();
    paginaSiguiente();

    consultarAPI(); // Consulta la API en el backend de PHP

    idCliente();
    nombreCliente(); // Añade el nombre del cliente al objeto de cita
    seleccionarFecha(); // Añade la fecha de la cita en el objeto
    seleccionarHora(); // Añade la hora de la cita en el objeto

    mostrarResumen(); // Añade un resumen con los datos ingresados por el usuario
}

function mostrarSeccion() {
    // Ocultar la sección que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');// El punto antes de mostrar es solo para el query selector
    if(seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }    

    // Selecciona la sección con el paso...
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    // Quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if (tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    // Resalta el tab actual
    const tab =     document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');

}

function tabs() {
    const botones = document.querySelectorAll('.tabs button'); // Selecciona todos los botones de la clase tabs

    botones.forEach( boton => {
        boton.addEventListener('click', function (e) {
            paso = parseInt( e.target.dataset.paso);

            mostrarSeccion();

            botonesPaginador();

        });
    })
}

function botonesPaginador() {
    const paginaAnterior = document.querySelector('#anterior');// El #anterior se comunica con el id="anterior" del boton homónimo
    const paginaSiguiente = document.querySelector('#siguiente');// El #siguiente se comunica con el id="siguiente" del boton homónimo

    if(paso === 1) {
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }else if (paso === 3) {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');

        mostrarResumen();
    }else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }

    mostrarSeccion();
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function () {
        if(paso <= pasoInicial) return;        
        paso--;// Los signos menos menos (--) indica que debe restar de uno en uno
        botonesPaginador();
    })
}

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function () {
        if(paso >= pasoFinal) return;        
        paso++;// Los signos más más (++) indica que debe sumar de uno en uno
        botonesPaginador();
    })
}

async function consultarAPI() {// Una función asíncrona se ejecuta al mismo tiempo que otras. Con async nos aseguramos que nuestra función tenga un buen performance

    try {
        const url = '/api/servicios';
        const resultado = await fetch(url);// await depende de que tengamos async en la función. Si elimino el async entonces ya no funciona el await
        const servicios = await resultado.json();
        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach (servicio => {
        const { id, nombre, precio } = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function() {
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);// el appendChild agrega el párrafo con el nombreServicio dentro del div
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio) {
    const { id } = servicio;
    const { servicios } = cita;
    
    // identificar el elemento al que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    // Comprobar si un servicio ya fue agregado
    if( servicios.some( agregado => agregado.id === id ) ) {// some comprueba si ya fue agregado o no un ítem
        //Eliminar. Si ya está agregado y presionamos sobre el servicio nuevamente es que queremos quitarlo
        cita.servicios = servicios.filter( agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    }else {
        // Agregarlo
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }  
}

function idCliente() {
    cita.id = document.querySelector('#id').value;
}

function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha'); // Aquí estamos seleccionando el id de la fecha que agregamos en el index de la cita
    inputFecha.addEventListener('input', function(e) {
        
        const dia = new Date(e.target.value).getUTCDay();

        if( [1].includes(dia)) {
            //if( [6, 0].includes(dia)) {
            e.target.value = '';
            mostrarAlerta('Los días Lunes no estamos atendiendo', 'error', '.formulario');
        }else{
            cita.fecha = e.target.value;
        }
    })   
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if(hora < 10 || hora > 18) {
            e.target.value = '';
            mostrarAlerta('Horas no válidas', 'error', '.formulario');
        }else {
            cita.hora = e.target.value; 
            //console.log(cita);
        }
        
    })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    // Previene que se generen más de 1 alerta
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove();
    };

    // Scripting para crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    //const formulario = document.querySelector('#paso-2 p'); Aquí está el código para que el error aparezca en un párrafo encima del calendario
    referencia.appendChild(alerta);

    if(desaparece) {
        // Eliminar la alerta
        setTimeout( () => {
        alerta.remove();
        }, 3000); // El alerta desaperece después de transcurridos 3 segundos desde el evento
    }
    
}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    // Limpiar el contenido de resumen
    while (resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    if(Object.values(cita).includes('') || cita.servicios.length === 0 ) {// cita.servicios.length === 0 quiere decir que el usuario no seleccionó ningún servicio
        mostrarAlerta('Faltan datos de Servicios, Fecha u Hora', 'error', '.contenido-resumen', false);

        return;
    }
    

    // Formatear el div de resumen
    const { nombre, fecha, hora, servicios } = cita; 
    
    // Heading para Servicios en Resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);
    
    // Iterando y creando los servicios
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    })

    // Heading para la cita en Resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);

    const nombreCliente = document.createElement('P');// Creando un párrafo en el HTML
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    // Generar un nuevo párrafo con la cantidad de servicios contratados
    const cantidadServicios = document.createElement('P');
    cantidadServicios.innerHTML = `<span>Cantidad de Servicios:</span> ${servicios.length}`;

    // Generar un nuevo párrafo con el total a pagar 
    const total = servicios.reduce((total, servicio) => total + parseFloat(servicio.precio), 0);
    const totalParrafo = document.createElement('P');
    totalParrafo.innerHTML = `<span>Total:</span> $${total}`;

    // Formatear la fecha en español
    const fechaObj = new Date(fecha);// cada vez que utilizamos el new Date se descuenta un día
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date( Date.UTC(year, mes, dia));// cada vez que utilizamos el new Date se descuenta un día
    
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}
    const fechaFormateada = fechaUTC.toLocaleDateString('es-CO', opciones);

    const fechaCita = document.createElement('P');// Creando un párrafo en el HTML
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');// Creando un párrafo en el HTML
    horaCita.innerHTML = `<span>Hora:</span> ${hora} horas`;


    // Botón para crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(cantidadServicios);
    resumen.appendChild(totalParrafo);

    resumen.appendChild(botonReservar);
}

async function reservarCita() {
    
    const { nombre, fecha, hora, servicios, id } = cita;

    const idServicios = servicios.map( servicio => servicio.id );

    const datos = new FormData();
    
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);

    //console.log([...datos]); 
    
    try {
        // Petición hacia la API
        const url = '/api/citas'

        const respuesta = await fetch(url, {
        method: 'POST',
        body: datos
        });

        const resultado = await respuesta.json();
        console.log(resultado.resultado);

        if (resultado.resultado) {
            Swal.fire({
            icon: 'success',
            title: 'Cita Creada',
            text: 'Tu cita fue creada correctamente',
            button: 'OK'
            }).then( () => {
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            })
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Hubo un error al guardar la cita!'
          })
    }
   
    //console.log([...datos]); muestra que datos se van a enviar al servidor

}