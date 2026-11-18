
<?php

session_start();

// Verificar que el productor haya iniciado sesión
if (!isset($_SESSION["id_productor"])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . "/config/conexion.php";

// Obtener el identificador del productor que inició sesión
$id_productor = $_SESSION["id_productor"];

// Consultar únicamente sus mensajes
$sql = "SELECT
            m.id_mensaje,
            m.nombre_visitante,
            m.correo_visitante,
            m.telefono_visitante,
            m.mensaje,
            m.fecha_envio,
            p.nombre_producto,
            pr.nombres,
            pr.apellidos
        FROM mensajes m
        INNER JOIN productores pr
            ON m.id_productor = pr.id_productor
        LEFT JOIN productos p
            ON m.id_producto = p.id_producto
        WHERE m.id_productor = ?
        ORDER BY m.fecha_envio DESC";

$consulta = $conexion->prepare($sql);

if (!$consulta) {
    die("Error al preparar la consulta.");
}

$consulta->bind_param("i", $id_productor);
$consulta->execute();

$resultado = $consulta->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mis mensajes | AgroBolívar</title>

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
            <a href="cerrar_sesion.php">Cerrar sesión</a>
        </nav>

    </div>
</header>

<main class="seccion">

    <div class="contenedor">

        <div class="titulo-seccion">

            <span class="etiqueta">COMUNICACIÓN CON COMPRADORES</span>

            <h2>Mis mensajes recibidos</h2>

            <p>
                Bienvenido,
                <?php echo htmlspecialchars($_SESSION["nombre_productor"]); ?>.
                Aquí puedes consultar los mensajes relacionados con tus productos.
            </p>

        </div>

        <?php if ($resultado->num_rows > 0): ?>

            <?php while ($fila = $resultado->fetch_assoc()): ?>

                <div class="tarjeta-producto" style="margin-bottom: 20px;">

                    <h3>
                        <?php echo htmlspecialchars($fila["nombre_visitante"]); ?>
                    </h3>

                    <p>
                        <strong>Producto de interés:</strong>
                        <?php echo htmlspecialchars(
                            $fila["nombre_producto"] ?? "Consulta general"
                        ); ?>
                    </p>

                    <p>
                        <strong>Correo:</strong>
                        <?php echo htmlspecialchars(
                            $fila["correo_visitante"] ?? ""
                        ); ?>
                    </p>

                    <p>
                        <strong>Teléfono:</strong>
                        <?php echo htmlspecialchars(
                            $fila["telefono_visitante"] ?? ""
                        ); ?>
                    </p>

                    <p>
                        <strong>Mensaje:</strong>
                        <?php echo nl2br(htmlspecialchars($fila["mensaje"])); ?>
                    </p>

                    <p>
                        <strong>Fecha:</strong>
                        <?php echo htmlspecialchars($fila["fecha_envio"]); ?>
                    </p>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p style="text-align:center; padding:30px;">
                Todavía no has recibido mensajes.
            </p>

        <?php endif; ?>

    </div>

</main>

<footer class="pie-pagina">
    <div class="contenedor">
        <h3>🌱 AgroBolívar</h3>
        <p>Promoviendo los productos agrícolas de Bolívar Cauca.</p>
        <p class="derechos">Proyecto académico - UNAD | 2026</p>
    </div>
</footer>

</body>
</html>

<?php
$consulta->close();
?>

