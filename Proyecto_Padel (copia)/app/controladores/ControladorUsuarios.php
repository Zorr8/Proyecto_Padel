<?php
require_once __DIR__ . '/../modelos/ConexionDB.php';
require_once __DIR__ . '/../modelos/AdministradorDao.php';
require_once __DIR__ . '/../modelos/Administrador.php';
require_once __DIR__ . '/../modelos/UsuariosDao.php';
require_once __DIR__ . '/../modelos/Usuario.php';
require_once __DIR__ . '/../modelos/Sesion.php';

class ControladorUsuarios {
    public function registrar() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = htmlentities($_POST['email']);
            $password = htmlentities($_POST['password']);
            $nombre = htmlentities($_POST['nombre']);

            $connexionDB = new ConexionDB();
            $conn = $connexionDB->getConexion();

            if ($email === 'admin@prueba.com') {
                $administradoresDAO = new AdministradorDao($conn);

                if ($administradoresDAO->getByEmail($email) !== null) {
                    $error = "Ya hay un administrador con ese email";
                } else {
                    $administrador = new Administrador(null, $nombre, $email, $password);
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $administrador->setPassword($hashed_password);

                    if ($administradoresDAO->insert($administrador)) {
                        $connexionDB->cerrarConexion();
                        header("location: app/vistas/inicio.php");
                        exit(); // Importante salir del script después de la redirección
                    } else {
                        $error = "No se ha podido insertar el administrador";
                    }
                }
            } else {
                $usuariosDAO = new UsuariosDao($conn);

                if ($usuariosDAO->obtenerPorEmail($email) !== null) {
                    $error = "Ya hay un usuario con ese email";
                } else {
                    $usuario = new Usuario(null, $nombre, $email, $password);
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $usuario->setPassword($hashed_password);

                    if ($usuariosDAO->insert($usuario)) {
                        $connexionDB->cerrarConexion();
                        header("location: app/vistas/inicio.php");
                        exit(); // Importante salir del script después de la redirección
                    } else {
                        $error = "No se ha podido insertar el usuario";
                    }
                }
            }

            $connexionDB->cerrarConexion();
        }

        if ($error) {
            echo $error; // Muestra el error en la pantalla
        }
    }

    public function login() {
        $connexionDB = new ConexionDB();
        $conn = $connexionDB->getConexion();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);

            if ($email && $password) {
                $administradoresDAO = new AdministradorDao($conn);
                $administrador = $administradoresDAO->getByEmail($email);

                if (is_object($administrador) && $administrador instanceof Administrador) {
                    if (password_verify($password, $administrador->getPassword())) {
                        session_start();
                        $_SESSION['admin_id'] = $administrador->getId();
                        $_SESSION['admin_email'] = $administrador->getEmail();
                        $_SESSION['admin_nombre'] = $administrador->getNombre();

                        header("location: app/vistas/administrar.php");
                        exit();
                    } else {
                        $_SESSION['mensaje_error'] = "Email o contraseña incorrectos";
                    }
                } else {
                    $usuariosDAO = new UsuariosDao($conn);
                    $usuario = $usuariosDAO->obtenerPorEmail($email);

                    if (is_object($usuario) && $usuario instanceof Usuario) {
                        if (password_verify($password, $usuario->getPassword())) {
                            session_start();
                            $_SESSION['usuario_id'] = $usuario->getIdUsuario();
                            $_SESSION['usuario_email'] = $usuario->getEmail();
                            $_SESSION['usuario_nombre'] = $usuario->getNombre();

                            header("location: app/vistas/reservas.php");
                            exit();
                        } else {
                            $_SESSION['mensaje_error'] = "Email o contraseña incorrectos";
                        }
                    } else {
                        $_SESSION['mensaje_error'] = "Usuario no encontrado";
                    }
                }
            } else {
                $_SESSION['mensaje_error'] = "Por favor, ingrese tanto el email como la contraseña.";
            }
        }

        header('location: app/vistas/administrar.php');
        exit();
    }

    public function inicio() {
        header('Location: app/vistas/inicio.php');
        exit();
    }

    public function logout() {
        Sesion::cerrarSesion();
        header('location: app/vistas/inicio.php');
    }
}
?>





