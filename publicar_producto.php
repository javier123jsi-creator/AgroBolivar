
<?php
session_start();

// Verificar que el productor haya iniciado sesión
if (!isset($_SESSION["id_productor"])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . "/config/conexion.php";

$id_productor = (int) $_SESSION["id_productor"];
$nombre_productor = $_SESSION["nombre_productor"] ?? "Productor";

$mensaje = "";
$tipoMensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre_producto = trim($_POST["nombre_producto"] ?? "");
    $categoria = trim($_POST["categoria"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $precio = $_POST["precio"] ?? "";
    $disponibilidad = trim($_POST["disponibilidad"] ?? "");

    $rutaImagen = null;

    // Validar los campos obligatorios
    if (
        $nombre_producto === "" ||
        $categoria === "" ||
        $precio === "" ||
        !is_numeric($precio) ||
        (float)$precio < 0 ||
        $disponibilidad === ""
    ) {
        $mensaje = "Verifica los campos obligatorios.";
        $tipoMensaje = "error";

    } else {

        // Verificar si se seleccionó una fotografía
        if (
            isset($_FILES["imagen"]) &&
            $_FILES["imagen"]["error"] !== UPLOAD_ERR_NO_FILE
        ) {

            if ($_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {

                $mensaje = "Ocurrió un error al cargar la fotografía.";
                $tipoMensaje = "error";

            } elseif ($_FILES["imagen"]["size"] > 3 * 1024 * 1024) {

                $mensaje = "La fotografía no debe superar los 3 MB.";
                $tipoMensaje = "error";

            } else {

                // Comprobar el tipo real de archivo
                $informacionImagen = getimagesize($_FILES["imagen"]["tmp_name"]);

                if ($informacionImagen === false) {

                    $mensaje = "El archivo seleccionado no es una imagen válida.";
                    $tipoMensaje = "error";

                } else {

                    $tipoArchivo = $informacionImagen["mime"];

                    $tiposPermitidos = [
                        "image/jpeg" => "jpg",
                        "image/png" => "png",
                        "image/webp" => "webp"
                    ];

                    if (!isset($tiposPermitidos[$tipoArchivo])) {

                        $mensaje = "Solo se permiten imágenes JPG, PNG o WEBP.";
                        $tipoMensaje = "error";

                    } else {

                        // Crear un nombre único para la fotografía
                        $nombreArchivo = bin2hex(random_bytes(16)) .
                            "." . $tiposPermitidos[$tipoArchivo];

                        $carpetaImagenes = __DIR__ . "/imagenes/";

                        if (!is_dir($carpetaImagenes)) {
                            mkdir($carpetaImagenes, 0755, true);
                        }

                        $destino = $carpetaImagenes . $nombreArchivo;

                        if (move_uploaded_file(
                            $_FILES["imagen"]["tmp_name"],
                            $destino
                        )) {

                            $rutaImagen = "imagenes/" . $nombreArchivo;

                        } else {

                            $mensaje = "No fue posible guardar la fotografía.";
                            $tipoMensaje = "error";
                        }
                    }
                }
            }
        }

        // Guardar el producto si no ocurrió ningún error
        if ($tipoMensaje !== "error") {

            $consulta = $conexion->prepare(
                "INSERT INTO productos
                (id_productor, nombre_producto, categoria,
                descripcion, precio, disponibilidad, imagen)
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            if ($consulta) {

                $precio = (float)$precio;

                $consulta->bind_param(
                    "isssdss",
                    $id_productor,
                    $nombre_producto,
                    $categoria,
                    $descripcion,
                    $precio,
                    $disponibilidad,
                    $rutaImagen
                );

                if ($consulta->execute()) {

                    $mensaje = "¡Producto publicado correctamente!";
                    $tipoMensaje = "exito";

                } else {

                    $mensaje = "No fue posible publicar el producto.";
                    $tipoMensaje = "error";

                    // Eliminar la fotografía si no se guardó el producto
                    if (
                        $rutaImagen !== null &&
                        file_exists(__DIR__ . "/" . $rutaImagen)
                    ) {
                        unlink(__DIR__ . "/" . $rutaImagen);
                    }
                }

                $consulta->close();

            } else {

                $mensaje = "No fue posible preparar la publicación.";
                $tipoMensaje = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Publicar productos | AgroBolívar</title>

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
            <a href="mensajes_productor.php">Mis mensajes</a>
            <a href="cerrar_sesion.php">Cerrar sesión</a>
        </nav>

    </div>
</header>

<main class="seccion">

    <div class="contenedor">

        <div class="titulo-seccion">
            <span class="etiqueta">CATÁLOGO AGRÍCOLA</span>

            <h2>Publicar un producto</h2>

            <p>
                Registra los productos agrícolas disponibles
                para darlos a conocer a posibles compradores.
            </p>
        </div>

        <form class="formulario-productor"
              method="POST"
              action=""
              enctype="multipart/form-data">

            <?php if ($mensaje !== ""): ?>
                <p style="padding:15px; margin-bottom:20px;
                border-radius:7px;
                background:<?php echo $tipoMensaje === 'exito' ? '#dff0df' : '#f8dddd'; ?>;
                color:<?php echo $tipoMensaje === 'exito' ? '#245d31' : '#a12626'; ?>;">
                    <?php echo htmlspecialchars($mensaje); ?>
                </p>
            <?php endif; ?>

            <div class="campo-formulario">
                <label>Productor registrado</label>

                <input type="text"
                       value="<?php echo htmlspecialchars($nombre_productor); ?>"
                       readonly>
            </div>

            <div class="campo-formulario">
                <label for="nombre_producto">Nombre del producto *</label>

                <input type="text"
                       id="nombre_producto"
                       name="nombre_producto"
                       placeholder="Ejemplo: Café"
                       required>
            </div>

            <div class="campo-formulario">
                <label for="categoria">Categoría *</label>

                <select id="categoria" name="categoria" required>
                    <option value="">Selecciona una categoría</option>
                    <option value="Café">Café</option>
                    <option value="Frutas">Frutas</option>
                    <option value="Tubérculos">Tubérculos</option>
                    <option value="Cereales">Cereales</option>
                    <option value="Plátano">Plátano</option>
                    <option value="Hortalizas">Hortalizas</option>
                    <option value="Otros">Otros</option>
                </select>
            </div>

            <div class="campo-formulario">
                <label for="descripcion">Descripción</label>

                <textarea id="descripcion"
                          name="descripcion"
                          rows="4"
                          placeholder="Describe las características del producto..."></textarea>
            </div>

            <div class="campo-formulario">
                <label for="precio">Precio en pesos colombianos (COP) *</label>

                <input type="number"
                       id="precio"
                       name="precio"
                       min="0"
                       step="0.01"
                       placeholder="Ejemplo: 15000"
                       required>
            </div>

            <div class="campo-formulario">
                <label for="disponibilidad">Disponibilidad *</label>

                <select id="disponibilidad" name="disponibilidad" required>
                    <option value="">Selecciona una opción</option>
                    <option value="Disponible">Disponible</option>
                    <option value="Agotado">Agotado</option>
                </select>
            </div>

            <div class="campo-formulario">
                <label for="imagen">Fotografía del producto</label>

                <input type="file"
                       id="imagen"
                       name="imagen"
                       accept="image/jpeg,image/png,image/webp">

                <small>
                    Formatos permitidos: JPG, PNG o WEBP.
                    Tamaño máximo: 3 MB.
                </small>
            </div>

            <button type="submit" class="boton boton-principal">
                Publicar producto
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
