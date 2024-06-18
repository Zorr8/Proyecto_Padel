<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../web/estilos/estilos.css">
    <title>Document</title>
    <style>
       body{
    background-image: url("../../web/imagenes/2.webp");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh; 
    margin: 0; 
}
    </style>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <div class="container-fluid">
  <div class="d-flex justify-content-end">
    <a href="registrar.php" class="btn btn-success mt-2 mr-2">Registrarse</a>
  </div>
</div>

  <div class="container-custom">
  <h1 class="titulo my-5 text-center">Iniciar Sesion</h1><br><br><br><br><br>
    <form action="../../index.php?accion=login" method="post">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Contraseña:</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <div class="form-group d-flex justify-content-center">
      <button type="submit" class="btn btn-success mt-2 mr-2">Iniciar Sesión</button>
      </div>
    </form>
  </div>
 
       
    
</body>
</html>