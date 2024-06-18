<?php

require_once 'app/controladores/ControladorTramos.php';
require_once 'app/controladores/ControladorUsuarios.php';
require_once 'app/controladores/ControladorReservas.php';
require_once 'app/modelos/UsuariosDao.php';
require_once 'app/modelos/ConexionDB.php';
require_once 'app/modelos/Sesion.php';
require_once 'app/modelos/Usuario.php';

require_once 'app/config/config.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si existe la cookie y no ha iniciado sesión, iniciamos sesión de forma automática
if (!Sesion::existeSesion() && isset($_COOKIE['sid'])) {
    $conexionDB = new ConexionDB();
    $conn = $conexionDB->getConexion();

    $usuariosDAO = new UsuariosDAO($conn);
    if ($usuario = $usuariosDAO->obtenerPorId($_COOKIE['sid'])) {
        Sesion::iniciarSesion($usuario);
    }
}

// Mapeo de acciones y controladores
$mapa = [
    'inicio' => ['controlador' => 'ControladorUsuarios', 'metodo' => 'inicio', 'privada' => false],
    'registrar' => ['controlador' => 'ControladorUsuarios', 'metodo' => 'registrar', 'privada' => false],
    'login' => ['controlador' => 'ControladorUsuarios', 'metodo' => 'login', 'privada' => false],
    'logout' => ['controlador' => 'ControladorUsuarios', 'metodo' => 'logout', 'privada' => true],
    'obtenerTramosDisponibles' => ['controlador' => 'ControladorTramos', 'metodo' => 'obtenerTramosDisponiblesPorFecha', 'privada' => false],
    'agregarReserva' => ['controlador' => 'ControladorReservas', 'metodo' => 'agregarReserva', 'privada' => true],
    'obtenerTramosConEstado' => ['controlador' => 'ControladorTramos', 'metodo' => 'obtenerTramosConEstado', 'privada' => false],
    'eliminarReserva' => ['controlador' => 'ControladorReservas', 'metodo' => 'eliminarReserva', 'privada' => true],
];

$accion = $_GET['accion'] ?? $_POST['accion'] ?? 'inicio';

if (array_key_exists($accion, $mapa)) {
    // Si la acción es privada, comprobar que el usuario ha iniciado sesión
    if ($mapa[$accion]['privada'] && !isset($_SESSION['usuario_id'])) {
        header('Location: index.php?accion=login');
        exit();
    }

    $controlador = $mapa[$accion]['controlador'];
    $metodo = $mapa[$accion]['metodo'];
    $objeto = new $controlador();

    // Manejo de parámetros específicos para agregarReserva
    if ($accion == 'agregarReserva' && isset($_POST['id_usuario'], $_POST['id_tramo'], $_POST['fecha'])) {
        $objeto->$metodo($_POST['id_usuario'], $_POST['id_tramo'], $_POST['fecha']);
    } elseif ($accion == 'eliminarReserva' && isset($_POST['id_reserva'])) {
        $objeto->$metodo($_POST['id_reserva']);
    } else {
        $objeto->$metodo();
    }
} else {
    // Página no encontrada
    header('HTTP/1.0 404 Not Found');
    echo 'Página no encontrada';
    exit();
}

// Si existe la cookie y no ha iniciado sesión, iniciamos sesión de forma automática
if (!Sesion::existeSesion() && isset($_COOKIE['sid'])) {
$conexionDB = new ConexionDB();
$conn = $conexionDB->getConexion();

$usuariosDAO = new UsuariosDAO($conn);
if ($usuario = $usuariosDAO->obtenerPorId($_COOKIE['sid'])) {
    Sesion::iniciarSesion($usuario);
}
}

// Si existe la cookie y no ha iniciado sesión, iniciamos sesión de forma automática
if (!Sesion::existeSesion() && isset($_COOKIE['sid'])) {
    $connexionDB = new ConexionDB();
    $conn = $connexionDB->getConexion();

    $usuariosDAO = new UsuariosDAO($conn);
    if ($usuario = $usuariosDAO->obtenerPorId($_COOKIE['sid'])) {
        Sesion::iniciarSesion($usuario);
    }
}



//Si la acción es privada compruebo que ha iniciado sesión, sino, lo echamos a index
// if(!isset($_SESSION['email']) && $mapa[$accion]['privada']){



//$acción ya tiene la acción a ejecutar, cogemos el controlador y metodo a ejecutar del mapa
$controlador = $mapa[$accion]['controlador'];
$metodo = $mapa[$accion]['metodo'];

//Ejecutamos el método de la clase controlador


?>
