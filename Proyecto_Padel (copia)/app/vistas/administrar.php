<?php
require_once '../controladores/ControladorReservas.php';

$controladorReservas = new ControladorReservas();
$reservas = $controladorReservas->obtenerReservas();
session_start();

if (isset($_SESSION['admin_nombre'])) {
    $nombreUsuario = $_SESSION['admin_nombre'];

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
    <title>Administrar Reservas</title>

    <!-- Incluir Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url("../../web/imagenes/reservas.png");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            margin: 0;
            padding: 20px; /* Añadir un espacio interior para separar del borde */
        }

        .contenedor-animacion {
            text-align: right;
            padding: 10px;
            margin-bottom: 20px;
        }

        .bienvenida {
            font-size: 20px;
            font-weight: bold;
            color: #fff;
        }

        .btn-personalizado {
            margin-left: 10px;
        }

        .card {
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        .card-body {
            padding: 15px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 8px;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4">Administrar Reservas</h1>

                <?php if (!empty($reservas)) : ?>
                    <table class="table" id="tablaReservas">
                        <thead>
                            <tr>
                                <th>ID Reserva</th>
                                <th>ID Usuario</th>
                                <th>ID Tramo</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservas as $reserva) : ?>
                                <tr id="reserva_<?php echo $reserva->getIdReserva(); ?>">
                                    <td><?php echo $reserva->getIdReserva(); ?></td>
                                    <td><?php echo $reserva->getIdUsuario(); ?></td>
                                    <td><?php echo $reserva->getIdTramo(); ?></td>
                                    <td><?php echo $reserva->getFecha(); ?></td>
                                    <td>
                                        <button data-id-reserva="<?php echo $reserva->getIdReserva(); ?>" class="btn btn-danger eliminar-reserva">Eliminar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p class="alert alert-info">No hay reservas disponibles.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Incluir jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
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
    </script>

    <!-- Incluir Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>


