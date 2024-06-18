<?php
require_once '../controladores/ControladorTramos.php';
require_once '../controladores/ControladorReservas.php';

 session_start();

   
    if (isset($_SESSION['usuario_nombre'])) {
        $nombreUsuario = $_SESSION['usuario_nombre'];
       
        echo "<div class='contenedor-animacion'>
                <p class='bienvenida mb-0'>Bienvenido/a, $nombreUsuario</p>
                <a href='../../index.php?accion=logout' class='btn btn-warning btn-personalizado'>Cerrar Sesión</a>
              </div>";
    } else {
        header('Location: index.php');
        exit();
    }
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../web/estilos/estilos.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
   <script src="../modelos/js.js"></script>
    <style>
       body{
        background-image: url("../../web/imagenes/reservas.png");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh; 
    margin: 0; 
}
    </style>
</head>
<body>
<body data-usuario-id="<?php echo $_SESSION['usuario_id'] ?? '0'; ?>">

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-animate-derecha"> <!-- Añade aquí la clase para la animación -->
                <img src="../../web/imagenes/Pista3.jpg" class="card-img-top" alt="Imagen Descriptiva">
                <div class="card-body">
                    <h5 class="card-title">Pista 1</h5>
                    <p class="card-text">Superficie: Sintetica</p>
                    <button id="mostrarCalendario" class="btn btn-primary mb-3">Calendario de Reservas</button>
                        <div  id="calendario" style="display:none;">
                        <button id="mesAnterior" class="btn btn-secondary">
                            <i class="fas fa-chevron-left"></i> 
                                </button>
                            <span id="mesAnio"></span>
                                <button id="mesSiguiente" class="btn btn-secondary">
                                 <i class="fas fa-chevron-right"></i>
                                </button>
                            <div id="diasSemana"></div>
                            <div id="diasCalendario"></div>
                        </div>
                       <!-- Contenedor para la Tabla de Tramos -->
                       
                       <div id="contenedorTablaTramos" class="col-md-0" style="display:none;">
                            <table id="tablaTramos" class="table">
                                <thead>
                                <tr>
                                    <th>ID Tramo</th>
                                    <th>Hora</th>
                                    <th>Estado</th>
                                    <th>Accion</th>
                                </tr>
                        </thead>
                        <tbody>
            <!-- Los tramos se insertarán aquí dinámicamente -->
                        </tbody>
                    </table>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>



<!-- Incluir jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tablaTramos').on('click', '.reservar-tramo', function() {
            var idTramo = $(this).data('id-tramo');
            var idUsuario = $('body').data('usuario-id');
            var fecha = $('#mesAnio').text(); // Ejemplo: tomar la fecha de algún elemento visible

            $.post('index.php?accion=agregarReserva', {
                id_usuario: idUsuario,
                id_tramo: idTramo,
                fecha: fecha
            }, function(response) {
                alert(response);
                // Aquí puedes realizar más acciones dependiendo de la respuesta del servidor
            });
        });
    });
</script>
</body>
</html>
