<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="contac_estilos.css">
    <link rel="shortcut icon" href="Img/ICONO.png" type="image/x-icon">
</head>
<body>
<?php
// Configuración de la conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "administración";

// Conectar a la base de datos
$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
  die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Obtener los datos del formulario
  $nombre = $_POST['nombre'];
  $correo = $_POST['correo'];
  $mensaje = $_POST['mensaje'];

  // Validar los datos
  $errores = array();
  if (empty($nombre)) {
    $errores[] = "Debe ingresar su nombre";
  }
  if (empty($mensaje)) {
    $errores[] = "Debe ingresar un mensaje";
  }

  // Si hay errores, mostrarlos
  if (!empty($errores)) {
    echo "<ul>";
    foreach ($errores as $error) {
      echo "<li>$error</li>";
    }
    echo "</ul>";
  } else {
    // Insertar datos en la base de datos
    $fecha_envio = date('Y-m-d H:i:s');
    $sql = "INSERT INTO formularios (nombre, correo, mensaje, fecha_envio) VALUES ('$nombre', '$correo', '$mensaje', '$fecha_envio')";
    if (mysqli_query($conn, $sql)) {
      // Enviar correo de confirmación
      $to = $correo;
      $subject = "Confirmación de formulario";
      $message = "Estimado usuario, su formulario fue recibido. En breve lo contactaremos.";
      $headers = "From: lorenzo@example.com" . "\r\n" .
                 "Reply-To: lorenzo@example.com" . "\r\n" .
                 "X-Mailer: PHP/" . phpversion();

      if (@mail($to, $subject, $message, $headers)) {
        echo "<div id='mensaje-enviado'>¡Gracias por su mensaje! En breve nos pondremos en contacto con usted.</div>";
        echo "<script>document.getElementById('contacto-form').style.display = 'none';</script>"; // Oculta el formulario
      } else {
        echo "<p>Ha ocurrido un error al enviar su mensaje. Por favor, inténtelo de nuevo más tarde.</p>";
      }
    } else {
      echo "<p>Ha ocurrido un error al enviar su mensaje. Por favor, inténtelo de nuevo más tarde.</p>";
    }
  }
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>

<form id="contacto-form" method="post" onsubmit="return validarFormulario()">
  <div>
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" id="nombre">
  </div>
  <div>
    <label for="correo">Correo electrónico:</label>
    <input type="email" name="correo" id="correo">
  </div>
  <div>
    <label for="mensaje">Mensaje:</label>
    <textarea name="mensaje" id="mensaje"></textarea>
  </div>
  <button type="submit">Enviar</button>
  <button type="button" onclick="window.location.href='principal.php'">Volver</button>
</form>

<script>
function validarFormulario() {
  var formulario = document.getElementById("contacto-form");
  var nombre = formulario.elements["nombre"].value;
  var correo = formulario.elements["correo"].value;
  var mensaje = formulario.elements["mensaje"].value;

  var errores = [];
  if (nombre.trim() === "") {
    errores.push("Debe ingresar su nombre.");
  }
  if (correo.trim() === "") {
    errores.push("Debe ingresar su correo electrónico.");
  } else if (!/\S+@\S+\.\S+/.test(correo)) {
    errores.push("Debe ingresar un correo electrónico válido.");
  }
  if (mensaje.trim() === "") {
    errores.push("Debe ingresar un mensaje.");
  }

  if (errores.length > 0) {
    alert("Por favor, corrija los siguientes errores:\n\n" + errores.join("\n"));
    return false;
  }

  return true;
}
</script>

</body>
</html>