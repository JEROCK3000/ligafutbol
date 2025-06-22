<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Equipo</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

<div class="contenedor">
    <h2>Registrar Nuevo Equipo</h2>
    <a href="index.php?url=equipos/lista" class="opcion">← Volver al Listado</a>

    <?php if (!empty($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" class="formulario">
        <label for="nombre">Nombre del Equipo:</label>
        <input type="text" name="nombre" required>

        <button type="submit" class="opcion">💾 Guardar</button>
    </form>
</div>

</body>
</html>
