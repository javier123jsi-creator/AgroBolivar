
<?php
session_start();
require_once "config/conexion.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $telefono = trim($_POST["telefono"]);
    $contrasena = $_POST["contrasena"];

    $consulta = $conexion->prepare(
        "SELECT id_productor, nombres, apellidos, contrasena
         FROM productores
         WHERE telefono = ?"
    );

    $consulta->bind_param("s", $telefono);
    $consulta->execute();

    $resultado = $consulta->get_result();

    if ($resultado->num_rows == 1) {

        $productor = $resultado->fetch_assoc();

        if (password_verify($contrasena, $productor["contrasena"])) {

            session_regenerate_id(true);

            $_SESSION["id_productor"] = $productor["id_productor"];
            $_SESSION["nombre_productor"] = $productor["nombres"];

            header("Location: mensajes_productor.php");
            exit();

        } else {
            $error = "La contraseña ingresada es incorrecta.";
        }

    } else {
        $error = "No se encontró un productor con ese teléfono.";
    }

    $consulta->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | AgroBolívar</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <main class="seccion">
        <div class="contenedor">

            <div class="titulo-seccion">
                <span class="etiqueta">AGROBOLÍVAR</span>
                <h2>Ingreso de productores</h2>
                <p>Inicia sesión para consultar los mensajes de tus productos.</p>
            </div>

            <?php if ($error != ""): ?>
                <p style="color: red; text-align: center;">
                    <?php echo htmlspecialchars($error); ?>
                </p>
            <?php endif; ?>

            <form method="POST" action="login.php"
                  style="max-width: 450px; margin: 30px auto;">

                <label>Teléfono</label>
                <input type="text" name="telefono" required
                       style="width: 100%; padding: 12px; margin: 10px 0;">

                <label>Contraseña</label>
                <input type="password" name="contrasena" required
                       style="width: 100%; padding: 12px; margin: 10px 0;">

                <button type="submit" class="boton boton-principal">
                    Iniciar sesión
                </button>

            </form>

            <p style="text-align: center;">
                <a href="index.php">Volver al inicio</a>
            </p>

        </div>
    </main>

</body>
</html>
