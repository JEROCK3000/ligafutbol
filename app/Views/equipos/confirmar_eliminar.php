<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar inactivación</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

<div class="contenedor">
    <h2>Deshabilitar equipo: <?= htmlspecialchars($equipo['nombre']) ?></h2>
    <form method="post" action="index.php?url=equipos/eliminar/<?= $equipo['id'] ?>" class="formulario">
        <label>Motivo:</label>
        <textarea name="motivo" required rows="4" placeholder="Explica la razón de desactivación..."></textarea>
        <button type="submit" class="salir">🗑️ Confirmar Deshabilitación</button>
    </form>
</div>

</body>
</html>
