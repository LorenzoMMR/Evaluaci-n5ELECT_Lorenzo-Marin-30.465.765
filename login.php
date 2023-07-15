<?php
session_start();
// Conectar a la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'administración';
$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
  die('Error al conectar a la base de datos: ' . mysqli_connect_error());
}

// Procesar el inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $usuario = $_POST['usuario'];
  $contraseña = $_POST['contraseña'];

  $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND contrasena='$contraseña'";
  $resultado = mysqli_query($conn, $sql);
  if (!$resultado || mysqli_num_rows($resultado) == 0) {
    $error = 'Nombre de usuario o contraseña incorrectos.';
  } else {
    $_SESSION['usuario'] = $usuario;
    header('Location: administrador.php');
    exit;
  }
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Iniciar sesión</title>
  <link rel="stylesheet" type="text/css" href="estilo_login.css">
  <link rel="shortcut icon" href="Img/ICONO.png" type="image/x-icon">
</head>
<body>
  <h1>INICIAR SESION</h1>
  <?php if (isset($error)) { ?>
    <p><?php echo $error; ?></p>
  <?php } ?>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <div>
      <label for="usuario">Usuario:</label>
      <input type="text" name="usuario" id="usuario" required>
    </div>
    <div>
      <label for="contraseña">Contraseña:</label>
      <input type="password" name="contraseña" id="contraseña" required>
    </div>
    <button type="submit">Ingresar</button>
  </form>
</body>
</html>