<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Equipos Deshabilitados</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

<div class="contenedor">
    <h2>Equipos Deshabilitados</h2>
    <a href="index.php?url=equipos/lista" class="opcion">← Volver al Listado</a>

    <table class="tabla">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Entrenador</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipos as $i => $equipo): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($equipo['nombre']) ?></td>
                    <td><?= htmlspecialchars($equipo['entrenador'] ?? 'No asignado') ?></td>
                    <td>
                        <a href="index.php?url=equipos/confirmar_habilitar/<?= $equipo['id'] ?>"
                           onclick="return confirm('¿Deseas volver a habilitar este equipo?');">
                           ✅ Habilitar
                        </a>
                        <a href="index.php?url=equipos/historial/<?= $equipo['id'] ?>">📜 Ver Historial del Equipo</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($equipos) === 0): ?>
                <tr><td colspan="4">No hay equipos deshabilitados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
