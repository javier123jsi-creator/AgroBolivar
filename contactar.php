
<?php

require_once __DIR__ . "/config/conexion.php";

$mensaje = "";
$tipoMensaje = "";

$id_producto = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_producto = filter_input(INPUT_POST, "id_producto", FILTER_VALIDATE_INT);
}

if (!$id_producto || $id_producto <= 0) {
    die("Producto no válido. Regresa al catálogo.");
}

// Consultar el producto y su productor.
$consultaProducto = $conexion->prepare(
    "SELECT p.id_producto, p.nombre_producto,
            pr.id_productor, pr.nombres, pr.apellidos
     FROM productos p
     INNER JOIN productores pr
     ON p.id_productor = pr.id_productor
     WHERE p.id_producto = ?"
);

$consultaProducto->bind_param("i", $id_producto);
$consultaProducto->execute();

$resultadoProducto = $consultaProducto->get_result();
$producto = $resultadoProducto->fetch_assoc();

$consultaProducto->close();

if (!$producto) {
    die("El producto solicitado no existe.");
}

// Guardar el mensaje enviado por el visitante.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre_visitante"] ?? "");
    $correo = trim($_POST["correo_visitante"] ?? "");
    $telefono = trim($_POST["telefono_visitante"] ?? "");
    $contenido = trim($_POST["mensaje"] ?? "");

    if ($nombre === "" || $contenido === "") {

        $mensaje = "Completa tu nombre y el mensaje.";
        $tipoMensaje = "error";

    } elseif (
        $correo !== "" &&
        !filter_var($correo, FILTER_VALIDATE_EMAIL)
    ) {

        $mensaje = "Ingresa un correo electrónico válido.";
        $tipoMensaje = "error";

    } else {

        $id_productor = (int)$producto["id_productor"];
        $id_producto = (int)$producto["id_producto"];

        $consulta = $conexion->prepare(
            "INSERT INTO mensajes
            (id_productor, id_producto, nombre_visitante,
            correo_visitante, telefono_visitante, mensaje)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        if ($consulta) {

            $consulta->bind_param(
                "iissss",
                $id_productor,
                $id_producto,
                $nombre,
                $correo,
                $telefono,
                $contenido
            );

            if ($consulta->execute()) {
                $mensaje = "¡Mensaje enviado correctamente! El productor podrá consultar tu solicitud.";
                $tipoMensaje = "exito";
            } else {
                $mensaje = "No fue posible enviar el mensaje.";
                $tipoMensaje = "error";
            }

            $consulta->close();

        } else {
            $mensaje = "No fue posible preparar el envío.";
            $tipoMensaje = "error";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contactar productor | AgroBolívar</title>

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
            <a href="catalogo.php">Catálogo</a>
        </nav>

    </div>
</header>

<main class="seccion">

    <div class="contenedor">

        <div class="titulo-seccion">
            <span class="etiqueta">COMUNICACIÓN DIRECTA</span>

            <h2>Contactar al productor</h2>

            <p>
                Envía una consulta sobre el producto que te interesa.
            </p>
        </div>

        <form class="formulario-productor" method="POST" action="">

            <input type="hidden" name="id_producto"
                   value="<?php echo (int)$producto["id_producto"]; ?>">

            <div class="productos-mensaje" style="margin-bottom:25px;">
                <h3>
                    <?php echo htmlspecialchars($producto["nombre_producto"]); ?>
                </h3>

                <p>
                    Productor:
                    <?php echo htmlspecialchars(
                        $producto["nombres"] . " " . $producto["apellidos"]
                    ); ?>
                </p>
            </div>

            <?php if ($mensaje !== ""): ?>
                <p style="padding:15px; margin-bottom:20px;
                border-radius:7px;
                background:<?php echo $tipoMensaje === 'exito' ? '#dff0df' : '#f8dddd'; ?>;
                color:<?php echo $tipoMensaje === 'exito' ? '#245d31' : '#a12626'; ?>;">
                    <?php echo htmlspecialchars($mensaje); ?>
                </p>
            <?php endif; ?>

            <div class="campo-formulario">
                <label for="nombre_visitante">Tu nombre *</label>
                <input type="text" id="nombre_visitante"
                       name="nombre_visitante" required
                       placeholder="Escribe tu nombre">
            </div>

            <div class="campo-formulario">
                <label for="correo_visitante">Correo electrónico</label>
                <input type="email" id="correo_visitante"
                       name="correo_visitante"
                       placeholder="ejemplo@correo.com">
            </div>

            <div class="campo-formulario">
                <label for="telefono_visitante">Teléfono</label>
                <input type="tel" id="telefono_visitante"
                       name="telefono_visitante"
                       placeholder="Número de contacto">
            </div>

            <div class="campo-formulario">
                <label for="mensaje">Tu consulta *</label>
                <textarea id="mensaje" name="mensaje"
                          rows="5" required
                          placeholder="Escribe tu consulta sobre el producto..."></textarea>
            </div>

            <button type="submit" class="boton boton-principal">
                Enviar mensaje
            </button>

            <p style="margin-top:15px; font-size:13px; color:#666;">
                Tus datos de contacto serán almacenados para facilitar
                la comunicación relacionada con esta consulta.
            </p>

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
