
<?php
$nombrePlataforma = "AgroBolívar";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $nombrePlataforma; ?> | Productos del campo</title>

    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <!-- ENCABEZADO -->
    <header class="encabezado">
        <div class="contenedor navegacion">

            <a href="index.php" class="logo">
                <span class="logo-icono">🌱</span>
                <span>Agro<span class="logo-verde">Bolívar</span></span>
            </a>

            <nav class="menu">
            <a href="index.php">Inicio</a>
            <a href="#nosotros">Nosotros</a>
            <a href="catalogo.php">Productos</a>
            <a href="registro_productor.php">Registrarme</a>
            <a href="login.php">Iniciar sesión</a>
            <a href="#contacto">Contacto</a>
            </nav>

            <a href="login.php" class="boton boton-menu">
             Iniciar sesión
            </a>

        </div>
    </header>

    <!-- SECCIÓN PRINCIPAL -->
    <main>

        <section class="portada">
            <div class="contenedor portada-contenido">

                <div class="portada-texto">

                    <span class="etiqueta">
                        PRODUCTOS AGRÍCOLAS DE BOLÍVAR CAUCA
                    </span>

                    <h1>
                        Del campo a nuevas
                        <span>oportunidades.</span>
                    </h1>

                    <p>
                        Conectamos a los pequeños productores agrícolas
                        con personas interesadas en conocer y adquirir
                        los productos de nuestra región.
                    </p>

                    <div class="portada-botones">
                        <a href="catalogo.php" class="boton boton-principal">
                            Conocer productos
                        </a>

                        <a href="#nosotros" class="boton boton-secundario">
                            Conocer más
                        </a>
                    </div>

                    <div class="mensaje-region">
                        <span>📍</span>
                        <span>Apoyando el campo de Bolívar Cauca.</span>
                    </div>

                </div>

                <div class="portada-imagen">
                    <img
                        src="imagenes/bolivar-cauca.jpg"
                        alt="Paisaje rural de Bolívar Cauca"
                    >
                </div>

            </div>
        </section>

        <!-- NOSOTROS -->
        <section class="seccion nosotros" id="nosotros">

            <div class="contenedor">

                <div class="titulo-seccion">
                    <span class="etiqueta">NUESTRA INICIATIVA</span>

                    <h2>El trabajo del campo merece ser conocido</h2>

                    <p>
                        AgroBolívar es una iniciativa tecnológica que busca
                        fortalecer la visibilidad de los pequeños productores
                        agrícolas del municipio de Bolívar Cauca.
                    </p>
                </div>

                <div class="beneficios">

                    <article class="tarjeta">
                        <div class="beneficio-icono">🌾</div>
                        <h3>Productos agrícolas</h3>
                        <p>
                            Conoce los productos cultivados por los
                            productores de nuestra región.
                        </p>
                    </article>

                    <article class="tarjeta">
                        <div class="beneficio-icono">📸</div>
                        <h3>Mayor visibilidad</h3>
                        <p>
                            Consulta fotografías, descripciones y
                            precios de los productos disponibles.
                        </p>
                    </article>

                    <article class="tarjeta">
                        <div class="beneficio-icono">🤝</div>
                        <h3>Contacto directo</h3>
                        <p>
                            Facilita el acercamiento entre productores
                            agrícolas y posibles compradores.
                        </p>
                    </article>

                </div>

            </div>

        </section>

        <!-- PRODUCTOS -->
        <section class="seccion productos" id="productos">

            <div class="contenedor">

                <div class="titulo-seccion">
                    <span class="etiqueta">DEL CAMPO A TU ALCANCE</span>

                    <h2>Conoce nuestros productos agrícolas</h2>

                    <p>
                        Descubre la variedad de productos que se cultivan
                        en el municipio de Bolívar Cauca.
                    </p>
                </div>

                <div class="productos-mensaje">

                    <span>🌱</span>

                    <h3>Descubre los productos de nuestra región</h3>

                    <p>
                        Consulta nuestro catálogo, conoce los productos agrícolas
                        y contacta directamente con sus productores.
                    </p>

                    <a href="catalogo.php" class="boton boton-principal">
                        Ver catálogo
                    </a>

                    <a href="registro_productor.php" class="boton boton-secundario">
                        Registrar mi emprendimiento agrícola
                    </a>

                </div>
                

            </div>

        </section>

        <!-- ACCESO PARA PRODUCTORES -->
        <section class="seccion">

            <div class="contenedor">

                <div class="titulo-seccion">
                    <span class="etiqueta">PARTICIPA EN AGROBOLÍVAR</span>

                    <h2>¿Eres productor agrícola?</h2>

                    <p>
                        Registra tus datos y comparte información sobre
                        los productos que cultivas para darles mayor
                        visibilidad.
                    </p>

                    <div class="portada-botones">
                        <a href="registro_productor.php" class="boton boton-principal">
                            Registrar productor
                        </a>

                        <a href="publicar_producto.php" class="boton boton-secundario">
                            Publicar producto
                        </a>
                    </div>

                </div>

            </div>

        </section>

        <!-- CONTACTO -->
        <section class="contacto" id="contacto">

            <div class="contenedor contacto-contenido">

                <div>
                    <h2>El campo de Bolívar tiene mucho que ofrecer.</h2>

                    <p>
                        Conoce, descubre y apoya los productos agrícolas
                        de nuestros pequeños productores.
                    </p>
                </div>

                <a href="catalogo.php" class="boton boton-claro">
                     Explorar AgroBolívar
                </a>

            </div>

        </section>

    </main> 

    <!-- PIE DE PÁGINA -->
    <footer class="pie-pagina">

        <div class="contenedor">

            <h3>🌱 AgroBolívar</h3>

            <p>
                Plataforma para la promoción de productos agrícolas
                de Bolívar Cauca.
            </p>

            <p class="derechos">
                Proyecto académico - UNAD | 2026
            </p>

        </div>

    </footer>

</body>
</html>
