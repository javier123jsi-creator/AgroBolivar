
<?php

require_once __DIR__ . "/config/conexion.php";

// Recibir los filtros del formulario
$busqueda = trim($_GET["buscar"] ?? "");
$categoriaSeleccionada = trim($_GET["categoria"] ?? "");

// Construir la consulta
$sql = "SELECT
            productos.id_producto,
            productos.nombre_producto,
            productos.categoria,
            productos.descripcion,
            productos.precio,
            productos.disponibilidad,
            productos.imagen,
            productores.nombres,
            productores.apellidos,
            productores.vereda,
            productores.telefono
        FROM productos
        INNER JOIN productores
        ON productos.id_productor = productores.id_productor
        WHERE 1=1";

$parametros = [];
$tipos = "";

// Aplicar búsqueda por nombre, productor o vereda
if ($busqueda !== "") {

    $sql .= " AND (
        productos.nombre_producto LIKE ?
        OR productores.nombres LIKE ?
        OR productores.apellidos LIKE ?
        OR productores.vereda LIKE ?
    )";

    $textoBusqueda = "%" . $busqueda . "%";

    $parametros[] = $textoBusqueda;
    $parametros[] = $textoBusqueda;
    $parametros[] = $textoBusqueda;
    $parametros[] = $textoBusqueda;

    $tipos .= "ssss";
}

// Aplicar filtro por categoría
if ($categoriaSeleccionada !== "") {

    $sql .= " AND productos.categoria = ?";

    $parametros[] = $categoriaSeleccionada;

    $tipos .= "s";
}

$sql .= " ORDER BY productos.fecha_publicacion DESC";

// Preparar y ejecutar consulta
$consulta = $conexion->prepare($sql);

if (!$consulta) {
    die("Error al preparar la consulta: " . $conexion->error);
}

if (!empty($parametros)) {
    $consulta->bind_param($tipos, ...$parametros);
}

$consulta->execute();

$resultado = $consulta->get_result();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Catálogo agrícola | AgroBolívar</title>

    <link rel="stylesheet" href="css/estilos.css">

    <style>
        .imagen-producto {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .producto-sin-imagen {
            width: 100%;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 55px;
            background: #edf4e9;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .filtros-catalogo {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin: 30px 0;
            padding: 20px;
            background: #f5f8f3;
            border-radius: 12px;
        }

        .filtros-catalogo input,
        .filtros-catalogo select {
            flex: 1;
            min-width: 180px;
            padding: 13px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font-size: 15px;
        }

        .boton-filtro {
            padding: 13px 22px;
            background: #285c38;
            color: white;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-size: 15px;
        }

        .boton-limpiar {
            padding: 13px 22px;
            background: #e1e7df;
            color: #333;
            border-radius: 7px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .resultado-filtro {
            margin-bottom: 20px;
            color: #555;
        }
    </style>
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
            <a href="registro_productor.php">Productores</a>
            <a href="publicar_producto.php">Publicar producto</a>
        </nav>

    </div>
</header>

<main class="seccion productos">

    <div class="contenedor">

        <div class="titulo-seccion">

            <span class="etiqueta">PRODUCTOS DE NUESTRA REGIÓN</span>

            <h2>Catálogo de productos agrícolas</h2>

            <p>
                Conoce los productos ofrecidos por los pequeños
                productores de Bolívar Cauca.
            </p>

        </div>

        <!-- Buscador y filtros -->
        <form method="GET" action="catalogo.php"
              class="filtros-catalogo">

            <input
                type="search"
                name="buscar"
                placeholder="Buscar producto, productor o vereda..."
                value="<?php echo htmlspecialchars($busqueda); ?>"
            >

            <select name="categoria">
                <option value="">Todas las categorías</option>

                <?php
                $categorias = [
                    "Café",
                    "Frutas",
                    "Tubérculos",
                    "Cereales",
                    "Plátano",
                    "Hortalizas",
                    "Otros"
                ];

                foreach ($categorias as $categoria):
                ?>

                    <option
                        value="<?php echo htmlspecialchars($categoria); ?>"
                        <?php echo $categoriaSeleccionada === $categoria ? "selected" : ""; ?>
                    >
                        <?php echo htmlspecialchars($categoria); ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <button type="submit" class="boton-filtro">
                Buscar
            </button>

            <a href="catalogo.php" class="boton-limpiar">
                Limpiar filtros
            </a>

        </form>

        <p class="resultado-filtro">
            Productos encontrados: <strong><?php echo $resultado->num_rows; ?></strong>
        </p>

        <div class="catalogo-productos">

            <?php if ($resultado->num_rows > 0): ?>

                <?php while ($producto = $resultado->fetch_assoc()): ?>

                    <article class="tarjeta-producto">

                        <?php if (!empty($producto["imagen"]) &&
                            file_exists(__DIR__ . "/" . $producto["imagen"])): ?>

                            <img
                                class="imagen-producto"
                                src="<?php echo htmlspecialchars($producto["imagen"]); ?>"
                                alt="<?php echo htmlspecialchars($producto["nombre_producto"]); ?>"
                                loading="lazy"
                            >

                        <?php else: ?>

                            <div class="producto-sin-imagen">🌾</div>

                        <?php endif; ?>

                        <span class="producto-categoria">
                            <?php echo htmlspecialchars($producto["categoria"]); ?>
                        </span>

                        <h3>
                            <?php echo htmlspecialchars($producto["nombre_producto"]); ?>
                        </h3>

                        <p>
                            <?php echo htmlspecialchars(
                                $producto["descripcion"] ?? "Sin descripción disponible."
                            ); ?>
                        </p>

                        <div class="producto-precio">
                            $<?php echo number_format((float)$producto["precio"], 0, ",", "."); ?> COP
                        </div>

                        <p class="producto-disponibilidad">
                            <?php echo htmlspecialchars($producto["disponibilidad"]); ?>
                        </p>

                        <div class="producto-productor">

                            <strong>Productor:</strong>

                            <?php echo htmlspecialchars(
                                $producto["nombres"] . " " . $producto["apellidos"]
                            ); ?>

                            <br>

                            <strong>Vereda:</strong>

                            <?php echo htmlspecialchars(
                                $producto["vereda"] ?: "No especificada"
                            ); ?>

                        </div>

                        <a
                            class="boton boton-principal"
                            href="contactar.php?id=<?php echo (int)$producto["id_producto"]; ?>"
                        >
                            Contactar productor
                        </a>

                    </article>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="productos-mensaje">
                    <span>🔎</span>
                    <h3>No encontramos productos</h3>
                    <p>
                        Intenta cambiar la búsqueda o seleccionar
                        otra categoría.
                    </p>
                </div>

            <?php endif; ?>

        </div>

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
