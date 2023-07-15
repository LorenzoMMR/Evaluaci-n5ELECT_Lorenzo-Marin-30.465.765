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

// Creación de usuario
if (isset($_POST['crear_usuario'])) {
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    if ($contrasena !== $confirmar_contrasena) {
        $error = "Las contraseñas no coinciden.";
    } else {
        $sql = "INSERT INTO usuarios (usuario, correo, contrasena) VALUES ('$usuario', '$correo', '$contrasena')";

        if ($conn->query($sql) === TRUE) {
            $exito = "Usuario creado exitosamente.";
        } else {
            $error = "Error al crear el usuario: " . $conn->error;
        }
    }
}

// Eliminación de usuario
if (isset($_GET['eliminar_usuario'])) {
    $id = $_GET['eliminar_usuario'];

    $sql = "DELETE FROM usuarios WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $exito = "Usuario eliminado exitosamente.";
    } else {
        $error = "Error al eliminar el usuario: " . $conn->error;
    }
}

// Obtención de usuarios
$sql = "SELECT * FROM usuarios";
$resultado = $conn->query($sql);
$usuarios = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $usuarios[] = $fila;
    }
}

// Creación de formulario
if (isset($_POST['crear_formulario'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $mensaje = $_POST['mensaje'];

    $sql = "INSERT INTO formularios (nombre, correo, mensaje) VALUES ('$nombre', '$correo', '$mensaje')";

    if ($conn->query($sql) === TRUE) {
        $exito = "Formulario creado exitosamente.";
    } else {
        $error = "Error al crear el formulario: " . $conn->error;
    }
}

// Edición de formulario
if (isset($_POST['editar_formulario'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $mensaje = $_POST['mensaje'];

    $sql = "UPDATE formularios SET nombre='$nombre', correo='$correo', mensaje='$mensaje' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $exito = "Formulario actualizado exitosamente.";
    } else {
        $error = "Error al actualizar el formulario: " . $conn->error;
    }
}

// Eliminación de formulario
if (isset($_GET['eliminar_formulario'])) {
    $id = $_GET['eliminar_formulario'];

    $sql = "DELETE FROM formularios WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $exito = "Formulario eliminado exitosamente.";
    } else {
        $error = "Error al eliminar el formulario: " . $conn->error;
    }
}

// Obtención de formularios
$sql = "SELECT * FROM formularios";
$resultado = $conn->query($sql);
$formularios = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $formularios[] = $fila;
    }
}

// Cierre de la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panel de administración</title>
    <link rel="stylesheet" href="administrador_estilos.css">
    <link rel="shortcut icon" href="Img/ICONO.png" type="image/x-icon">
    <style>
        /* Estilos CSS extra */
        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }

        .exito {
            color: green;
        }

        .error {
            color: red;
        }
    
    </style>
</head>
<body>
<h1>Panel de administración</h1>
<nav>
<ul>
    <li><a href="#usuarios">Usuarios</a></li>
    <li><a href="#formularios">Formularios</a></li>
    <li><a href="principal.php">Cerrar sesión(Inicio)</a></li>
</ul>
</nav>

<?php if (isset($exito)): ?>
    <p class="exito"><?php echo $exito; ?></p>
<?php endif; ?>

<?php if (isset($error)): ?>
    <p class="error"><?php echo $error; ?></p>
<?php endif; ?>

<section id="usuarios">
    <h2>Usuarios</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Correo electrónico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo $usuario['id']; ?></td>
                    <td><?php echo $usuario['usuario']; ?></td>
                    <td><?php echo $usuario['correo']; ?></td>
                    <td>
                    <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn-editar">Editar</a>
                    <a href="?eliminar_usuario=<?php echo $usuario['id']; ?>" class="btn-eliminar" onclick="if(!confirm('¿Estás seguro de que quieres eliminar este usuario?')){return false;}">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Crear usuario</h3>

    <form method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" required>
        <br>
        <label for="correo">Correo electrónico:</label>
        <input type="email" name="correo" required>
        <br>
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required>
        <br>
        <label for="confirmar_contrasena">Confirmar contraseña:</label>
        <input type="password" name="confirmar_contrasena" required>
        <br>
        <input type="submit" name="crear_usuario" value="Crear">
    </form>
</section>

<section id="formularios">
    <h2>Formularios</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo electrónico</th>
                <th>Mensaje</th>
                <th>Fecha de envío</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($formularios as $formulario): ?>
                <tr>
                    <td><?php echo $formulario['id']; ?></td>
                    <td><?php echo $formulario['nombre']; ?></td>
                    <td><?php echo $formulario['correo']; ?></td>
                    <td><?php echo $formulario['mensaje']; ?></td>
                    <td><?php echo $formulario['fecha_envio']; ?></td>
                    <td>
                    <button class="btn-editar" type="button" onclick="location.href='editar_formulario.php?id=<?php echo $formulario['id']; ?>'">Editar</button>
                    <button class="btn-eliminar" type="button" onclick="if(confirm('¿Estás seguro de que quieres eliminar este formulario?')){location.href='?eliminar_formulario=<?php echo $formulario['id']; ?>'}">Eliminar</button>
                    <button class="btn-generar-reporte" type="button" onclick="location.href='reporte_general.php'">Reporte General</button>
                    <button class="btn-generar-reporte-personalizado" type="button" onclick="location.href='reporte_personalizado.php?id=<?php echo $formulario['id']; ?>'">Reporte Personalizado</button>
                </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Crear formulario</h3>

    <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>
        <br>
        <label for="correo">Correo electrónico:</label>
        <input type="email" name="correo" required>
        <br>
        <label for="mensaje">Mensaje:</label>
        <textarea name="mensaje" required></textarea>
        <br>
        <input type="submit" name="crear_formulario" value="Crear">
    </form>
</section>
</body>
</html>