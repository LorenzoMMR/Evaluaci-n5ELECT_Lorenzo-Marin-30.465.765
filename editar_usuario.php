<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "administración";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Comprobación de sesión iniciada
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: principal.php");
    exit();
}

// Obtención del usuario a editar
$id = $_GET['id'];

$sql = "SELECT * FROM usuarios WHERE id=$id";
$resultado = $conn->query($sql);

if ($resultado->num_rows != 1) {
    header("Location: panel.php");
    exit();
}

$usuario = $resultado->fetch_assoc();

// Envío del formulario de edición
if (isset($_POST['editar_usuario'])) {
    $id = $_POST['id'];
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    if ($contrasena !== $confirmar_contrasena) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        $sql = "UPDATE usuarios SET usuario='$usuario', correo='$correo', contrasena='$contrasena_hash' WHERE id=$id";

        if ($conn->query($sql) === TRUE) {
            $exito = "Usuario actualizado exitosamente.";
            // Redirigir al archivo administrador.php después de mostrar el mensaje de éxito
            header('Location: administrador.php');
            exit();
        } else {
            $error = "Error al actualizar el usuario: " . $conn->error;
        }
    }
}

// Cierre de la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar usuario</title>
    <link rel="stylesheet" href="editar_usuario_estilos.css">
    <link rel="shortcut icon" href="Img/ICONO.png" type="image/x-icon">
    <style>
        /* Estilos CSS aquí */
        .exito {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Editar usuario</h1>

    <?php if (isset($exito)): ?>
        <p class="exito"><?php echo $exito; ?></p>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" value="<?php echo $usuario['usuario']; ?>" required>
        <br>
        <label for="correo">Correo:</label>
        <input type="email" name="correo" value="<?php echo $usuario['correo']; ?>" required>
        <br>
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required>
        <br>
        <label for="confirmar_contrasena">Confirmar contraseña:</label>
        <input type="password" name="confirmar_contrasena" required>
        <br>
        <input type="submit" name="editar_usuario" value="Actualizar usuario">
    </form>
</body>
</html>