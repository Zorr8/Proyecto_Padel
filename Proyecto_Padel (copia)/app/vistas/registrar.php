

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../web/estilos/estilos.css">
    <title>Formulario de Registro</title>
    <style>
       body{
    background-image: url("../../web/imagenes/1.webp");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 100vh; 
    margin: 0; 
}
    </style>
</head>
<body>





 <div class="container-custom">
 <h1 class="titulo my-0 text-center">Registro</h1><br><br><br>
 <form action="../../index.php?accion=registrar" method="post">
    <div class="form-group">
      <label for="nombre">Nombre:</label>
      <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
      <label for="password">Contraseña:</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="form-group d-flex justify-content-center">
    <button type="submit" class="btn btn-success mr-2">Registrarse</button>
    <a href="inicio.php" class="btn btn-success">Volver Atras</a>
    </div>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
