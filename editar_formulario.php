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

// Obtención del formulario a editar
$id = $_GET['id'];

$sql = "SELECT * FROM formularios WHERE id=$id";
$resultado = $conn->query($sql);

if ($resultado->num_rows != 1) {
    header("Location: panel.php");
    exit();
}

$formulario = $resultado->fetch_assoc();

// Envío del formulario de edición
if (isset($_POST['editar_formulario'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $mensaje = $_POST['mensaje'];
    $fecha_envio = $_POST['fecha_envio'];

    $sql = "UPDATE formularios SET nombre='$nombre', correo='$correo', mensaje='$mensaje', fecha_envio='$fecha_envio' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $exito = "Formulario actualizado exitosamente.";
        // Redirigir al archivo panel.php después de mostrar el mensaje de éxito
        header('Location: administrador.php');
        exit();
    } else {
        $error = "Error al actualizar el formulario: " . $conn->error;
    }
}

// Cierre de la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar formulario</title>
    <link rel="stylesheet" href="editar_formulario_estilos.css">
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
    <h1>Editar formulario</h1>

    <?php if (isset($exito)): ?>
        <p class="exito"><?php echo $exito; ?></p>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $formulario['id']; ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $formulario['nombre']; ?>" required>
        <br>
        <label for="correo">Correo:</label>
        <input type="email" name="correo" value="<?php echo $formulario['correo']; ?>" required>
        <br>
        <label for="mensaje">Mensaje:</label>
        <textarea name="mensaje" required><?php echo $formulario['mensaje']; ?></textarea>
        <br>
        <label for="fecha_envio">Fecha de envío:</label>
        <input type="datetime-local" name="fecha_envio" value="<?php echo date('Y-m-d\TH:i:s', strtotime($formulario['fecha_envio'])); ?>" required>
        <br>
        <input type="submit" name="editar_formulario" value="Actualizar formulario">
    </form>
</body>
</html>