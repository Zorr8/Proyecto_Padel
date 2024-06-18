var usuarioId = document.body.getAttribute('data-usuario-id');
function realizarReserva(id_tramo, fecha) {
    var usuarioId = document.body.getAttribute('data-usuario-id');
    console.log("Intentando realizar reserva con los siguientes datos:", {
        id_usuario: usuarioId,
        id_tramo: id_tramo,
        fecha: fecha
    }); // Debugging

    $.ajax({
        url: '/Proyecto_Padel/index.php',
        type: 'POST',
        data: {
            accion: 'agregarReserva',
            id_usuario: usuarioId,
            id_tramo: id_tramo,
            fecha: fecha
        },
        success: function(response) {
            console.log("Respuesta del servidor:", response); // Debugging
            alert("Reserva realizada con éxito.");
            actualizarUIPostReserva(id_tramo, fecha);
        },
        error: function(xhr, status, error) {
            console.error("Error al realizar la reserva: " + error);
            console.log("Respuesta del servidor:", xhr.responseText); // Debugging
            alert("No se pudo realizar la reserva.");
        }
    });
}

function actualizarUIPostReserva(id_tramo, fecha) {
    $('#tablaTramos tbody tr').each(function() {
        var fila = $(this);
        var idTramoFila = fila.find("td:first").text();

        if (idTramoFila == id_tramo) {
            fila.removeClass('table-danger').addClass('table-primary'); // Cambia al color azul para indicar "recién reservado"
            var botonCancelar = `<button onclick="cancelarReserva(${id_tramo}, '${fecha}')" class="btn btn-warning">Del</button>`;
            fila.find('td').eq(-2).html('Pendiente');
            fila.find('td:last').html(botonCancelar); // Inserta el botón de cancelar en la última columna
        }
    });
}

function cancelarReserva(id_tramo, fecha) {
    if (!confirm("¿Deseas cancelar esta reserva?")) return;

    $.ajax({
        url: '/Proyecto_Padel/index.php',
        type: 'POST',
        data: {
            accion: 'eliminarReserva', // Asegúrate de tener esta acción implementada en el backend
            id_tramo: id_tramo,
            fecha: fecha
        },
        success: function(response) {
            alert("Reserva cancelada con éxito.");
            actualizarUIPostCancelacion(id_tramo); // Actualiza la UI para reflejar la cancelación
        },
        error: function(xhr, status, error) {
            alert("Error al cancelar la reserva.");
            console.error("Error al cancelar la reserva:", error);
        }
    });
}
function actualizarUIPostCancelacion(id_tramo, fecha) {
    $('#tablaTramos tbody tr').each(function() {
        var fila = $(this);
        var idTramoFila = fila.find("td:first").text();

        if (idTramoFila == id_tramo) {
            fila.removeClass('table-primary').addClass(''); // Quita el color azul
            fila.find('td').eq(-2).html('Libre'); // Actualiza el estado a "Libre"

            // Reinserta el botón de reservar
            var botonReservar = `<button onclick="realizarReserva(${id_tramo}, '${fecha}')" class="btn btn-primary">Add</button>`;
            fila.find('td:last').html(botonReservar); // Asegúrate de que esta es la columna correcta para el botón
        }
    });
}

document.addEventListener("DOMContentLoaded", function() {
   
    var fechaActual = new Date();
    fechaActual.setDate(1); // Establecer al primer día del mes

    document.getElementById("mostrarCalendario").addEventListener("click", function() {
        document.getElementById("calendario").style.display = "block";
        generarCalendario(fechaActual);
    });

    document.getElementById("mesAnterior").addEventListener("click", function() {
        fechaActual.setMonth(fechaActual.getMonth() - 1);
        generarCalendario(fechaActual);
    });

    document.getElementById("mesSiguiente").addEventListener("click", function() {
        fechaActual.setMonth(fechaActual.getMonth() + 1);
        generarCalendario(fechaActual);
    });

    function generarCalendario(fecha) {
        var diasSemana = document.getElementById("diasSemana");
        diasSemana.innerHTML = ""; // Limpiar cabecera
        ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"].forEach(dia => {
            let div = document.createElement("div");
            div.textContent = dia;
            diasSemana.appendChild(div);
        });
    
        var diasCalendario = document.getElementById("diasCalendario");
        diasCalendario.innerHTML = ""; // Limpiar días
        var mesAnio = document.getElementById("mesAnio");
        mesAnio.textContent = fecha.toLocaleDateString('es', { month: 'long', year: 'numeric' });
    
        var primerDiaMes = new Date(fecha.getFullYear(), fecha.getMonth(), 1);
        var ultimoDiaMes = new Date(fecha.getFullYear(), fecha.getMonth() + 1, 0);
    
        // Ajustar el inicio del mes para comenzar por lunes
        var diaInicioMes = primerDiaMes.getDay();
        diaInicioMes = diaInicioMes === 0 ? 6 : diaInicioMes - 1; // Ajustar domingo (0) a 6 y restar 1 a los demás
    
        for (let i = 0; i < diaInicioMes; i++) { // Añadir días vacíos al inicio
            let celda = document.createElement("div");
            celda.classList.add("diaVacio");
            diasCalendario.appendChild(celda);
        }
    
        var diasEnMes = ultimoDiaMes.getDate();
        var fechaActual = new Date();
        fechaActual.setHours(0, 0, 0, 0); // Ignorar la hora para comparar solo fechas
    
        for (let dia = 1; dia <= diasEnMes; dia++) { // Añadir días del mes
            let celdaDia = document.createElement("div");
            celdaDia.textContent = dia;
            let fechaCelda = new Date(fecha.getFullYear(), fecha.getMonth(), dia);
    
            // Verificar si el día es anterior a la fecha actual
            if (fechaCelda < fechaActual) {
                celdaDia.classList.add("diaPasado");
            } else {
                celdaDia.classList.add("diaClickeable");
                celdaDia.addEventListener("click", function() {
                    console.log("Dia clickeado: ", fechaCelda);
                    cargarTramos(fechaCelda);
                });
            }
    
            diasCalendario.appendChild(celdaDia);
        }
    
        agregarDiasExtra(diaInicioMes, diasEnMes); // Función para añadir días vacíos al final si es necesario
    }
   
        // Función para cargar y mostrar los tramos
        function cargarTramos(fechaCelda) {
            // Formatea la fecha para que coincida con el formato esperado en el servidor, probablemente como 'YYYY-MM-DD'
            var fechaFormateada = fechaCelda.toISOString().split('T')[0];
        
            $.ajax({
                url: '/Proyecto_Padel/index.php', // Asegúrate de ajustar la URL según sea necesario
                type: 'POST',
                data: {
                    accion: 'obtenerTramosConEstado',
                    fecha: fechaFormateada // Envía la fecha formateada
                },
                dataType: 'json',
                success: function(tramos) {
                    console.log(tramos);
                    $('#contenedorTablaTramos').show();
                    // Limpia el contenedor de tramos existentes antes de rellenarlo con los nuevos datos
                    $('#tablaTramos tbody').empty();
        
                    // Itera sobre el array de tramos recibido en la respuesta
                    tramos.forEach(function(tramo) {
                        var botonReservar = ''; // Inicializa el botón como vacío

                        // Si el tramo no está reservado, añade el botón de reservar
                        if (!tramo.reservado) {
                            botonReservar = `<button onclick="realizarReserva(${tramo.id_tramo}, '${fechaFormateada}')" class="btn btn-primary">Add</button>`;
                        }
                        // Asigna una clase para colorear la fila según si el tramo está reservado
                        var clase = tramo.reservado ? 'table-danger' : ''; // 'table-danger' es una clase de Bootstrap para colorear la fila en rojo
                        // Construye la fila de la tabla con los datos del tramo
                        var fila = `<tr class="${clase}">
                                        <td>${tramo.id_tramo}</td>
                                        <td>${tramo.hora}</td>
                                        <td>${tramo.reservado ? 'Reservado' : 'Libre'}</td>
                                        <td><button onclick="realizarReserva(${tramo.id_tramo}, '${fechaFormateada}')" class="btn btn-primary ${tramo.reservado ? 'd-none' : ''}">Add</button></td>
                                    </tr>`;
                        // Añade la fila construida al cuerpo de la tabla
                        $('#tablaTramos tbody').append(fila);
                    });
                },
                error: function(xhr, status, error) {
                    // Maneja errores de la petición AJAX, por ejemplo, mostrando un mensaje de error
                    console.error("Error al cargar los tramos:", error);
                    console.log("Respuesta del servidor:", xhr.responseText);
                }
            });
        }
        
        
    
    
    
    
   
    
    
    

    function agregarDiasExtra(diaInicioMes, diasEnMes) {
        var totalDias = diaInicioMes + diasEnMes;
        var diasExtra = 7 - totalDias % 7;
        if (diasExtra < 7) {
            for (let i = 0; i < diasExtra; i++) {
                let celda = document.createElement("div");
                celda.classList.add("diaVacio");
                document.getElementById("diasCalendario").appendChild(celda);
            }
        }
    }
    
});
$(document).ready(function() {
    // Manejar el evento de clic en el botón para eliminar reserva desde la tabla de reservas
    $('#tablaReservas').on('click', '.eliminar-reserva', function() {
        var idReserva = $(this).data('id-reserva');
        
        console.log("ID de reserva:", idReserva); // Para depuración
        
        // Confirmar si el usuario realmente quiere eliminar la reserva
        if (confirm('¿Estás seguro de que deseas eliminar esta reserva?')) {
            // Realizar la solicitud AJAX para eliminar la reserva
            $.ajax({
                url: '/Proyecto_Padel/index.php',
                type: 'POST',
                data: {
                    accion: 'eliminarReserva',
                    id_reserva: idReserva
                },
                success: function(response) {
                    console.log("Respuesta del servidor (trim):", response.trim()); // Para depuración

                    if (response.trim() === 'OK') {
                        alert("Reserva eliminada correctamente.");

                        // Eliminar la fila de la tabla HTML
                        $('#reserva_' + idReserva).fadeOut(500, function() {
                            $(this).remove();
                        });
                    } else {
                        alert("Hubo un problema al eliminar la reserva.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error al eliminar la reserva:", error);
                    alert("No se pudo eliminar la reserva.");
                }
            });
        }
    });
});

