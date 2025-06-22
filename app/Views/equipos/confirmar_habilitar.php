<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar reactivación</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

<div class="contenedor">
    <h2>Habilitar equipo: <?= htmlspecialchars($equipo['nombre']) ?></h2>
    <form method="post" action="index.php?url=equipos/habilitar/<?= $equipo['id'] ?>" class="formulario">
        <label>Motivo:</label>
        <textarea name="motivo" required rows="4" placeholder="Explica la razón de reactivación..."></textarea>
        <button type="submit" class="opcion">✅ Confirmar Reactivación</button>
    </form>
</div>

</body>
</html>
