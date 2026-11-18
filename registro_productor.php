
<?php

require_once __DIR__ . "/config/conexion.php";

$mensaje = "";
$tipoMensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombres = trim($_POST["nombres"] ?? "");
    $apellidos = trim($_POST["apellidos"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $vereda = trim($_POST["vereda"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";
    $confirmar = $_POST["confirmar_contrasena"] ?? "";

    if ($nombres === "" || $apellidos === "" || $telefono === "" || $contrasena === "") {

        $mensaje = "Por favor completa los campos obligatorios.";
        $tipoMensaje = "error";

    } elseif (strlen($contrasena) < 8) {

        $mensaje = "La contraseña debe tener mínimo 8 caracteres.";
        $tipoMensaje = "error";

    } elseif ($contrasena !== $confirmar) {

        $mensaje = "Las contraseñas no coinciden.";
        $tipoMensaje = "error";

    } elseif ($correo !== "" && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {

        $mensaje = "Por favor ingresa un correo electrónico válido.";
        $tipoMensaje = "error";

    } else {

        $consulta = $conexion->prepare(
            "SELECT id_productor FROM productores WHERE telefono = ?"
        );

        $consulta->bind_param("s", $telefono);
        $consulta->execute();
        $resultado = $consulta->get_result();

        if ($resultado->num_rows > 0) {

            $mensaje = "Ya existe un productor registrado con ese teléfono.";
            $tipoMensaje = "error";

        } else {

            $contrasenaSegura = password_hash($contrasena, PASSWORD_DEFAULT);

            $insertar = $conexion->prepare(
                "INSERT INTO productores
                (nombres, apellidos, telefono, correo, vereda, descripcion, contrasena)
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            if ($insertar) {

                $insertar->bind_param(
                    "sssssss",
                    $nombres,
                    $apellidos,
                    $telefono,
                    $correo,
                    $vereda,
                    $descripcion,
                    $contrasenaSegura
                );

                if ($insertar->execute()) {
                    $mensaje = "¡Registro exitoso! Ya puedes iniciar sesión.";
                    $tipoMensaje = "exito";
                } else {
                    $mensaje = "No fue posible guardar el registro.";
                    $tipoMensaje = "error";
                }

                $insertar->close();

            } else {
                $mensaje = "No fue posible preparar el registro.";
                $tipoMensaje = "error";
            }
        }

        $consulta->close();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registro de productores | AgroBolívar</title>

    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <header class="encabezado">
        <div class="contenedor navegacion">

            <a href="index.php" class="logo">
                <span class="logo-icono">🌱</span>
                <span>Agro<span class="logo-verde">Bolívar</span></span>
            </a>

            <nav class="menu">
                <a href="index.php">Inicio</a>
                <a href="index.php#productos">Productos</a>
                <a href="index.php#contacto">Contacto</a>
                <a href="login.php">Iniciar sesión</a>
            </nav>

        </div>
    </header>

    <main class="seccion">

        <div class="contenedor">

            <div class="titulo-seccion">
                <span class="etiqueta">HAZ PARTE DE AGROBOLÍVAR</span>

                <h2>Registro de productores agrícolas</h2>

                <p>
                    Registra tus datos para dar a conocer tus productos
                    agrícolas y conectar con posibles compradores.
                </p>
            </div>

            <form class="formulario-productor" action="" method="POST">

                <?php if ($mensaje !== ""): ?>
                    <p style="padding: 15px; margin-bottom: 20px; border-radius: 7px;
                    background: <?php echo $tipoMensaje === 'exito' ? '#dff0df' : '#f8dddd'; ?>;
                    color: <?php echo $tipoMensaje === 'exito' ? '#245d31' : '#a12626'; ?>;">
                        <?php echo htmlspecialchars($mensaje); ?>
                    </p>
                <?php endif; ?>

                <div class="campo-formulario">
                    <label for="nombres">Nombres *</label>
                    <input type="text" id="nombres" name="nombres"
                           placeholder="Escribe tus nombres" required>
                </div>

                <div class="campo-formulario">
                    <label for="apellidos">Apellidos *</label>
                    <input type="text" id="apellidos" name="apellidos"
                           placeholder="Escribe tus apellidos" required>
                </div>

                <div class="campo-formulario">
                    <label for="telefono">Teléfono *</label>
                    <input type="tel" id="telefono" name="telefono"
                           placeholder="Número de contacto" required>
                </div>

                <div class="campo-formulario">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo"
                           placeholder="ejemplo@correo.com">
                </div>

                <div class="campo-formulario">
                    <label for="vereda">Vereda o sector</label>
                    <input type="text" id="vereda" name="vereda"
                           placeholder="Nombre de la vereda o sector">
                </div>

                <div class="campo-formulario">
                    <label for="descripcion">Descripción de tu actividad agrícola</label>
                    <textarea id="descripcion" name="descripcion"
                              rows="5"
                              placeholder="Cuéntanos qué productos cultivas..."></textarea>
                </div>

                <div class="campo-formulario">
                    <label for="contrasena">Crear contraseña *</label>
                    <input type="password" id="contrasena" name="contrasena"
                           minlength="8" required
                           placeholder="Mínimo 8 caracteres">
                </div>

                <div class="campo-formulario">
                    <label for="confirmar_contrasena">Confirmar contraseña *</label>
                    <input type="password" id="confirmar_contrasena"
                           name="confirmar_contrasena" minlength="8" required
                           placeholder="Escribe nuevamente tu contraseña">
                </div>

                <button type="submit" class="boton boton-principal">
                    Registrarme
                </button>

            </form>

        </div>

    </main>

    <footer class="pie-pagina">
        <div class="contenedor">
            <h3>🌱 AgroBolívar</h3>
            <p>Promoviendo los productos agrícolas de Bolívar, Cauca.</p>
            <p class="derechos">Proyecto académico - UNAD | 2026</p>
        </div>
    </footer>

</body>
</html>
