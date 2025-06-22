<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Equipos - Liga de Fútbol</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

    <div class="contenedor">
        <h2>Listado de Equipos</h2>
        <a href="index.php?url=equipos/deshabilitados" class="opcion">👁️ Ver Equipos Deshabilitados</a>
        <a href="index.php?url=usuarios/dashboard" class="opcion">← Volver al Dashboard</a>
        <table class="tabla">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre del Equipo</th>
                    <th>Entrenador</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipos as $i => $equipo): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($equipo['nombre']) ?></td>
                        <td><?= htmlspecialchars($equipo['entrenador'] ?? 'No asignado') ?></td>
                        <td>
                            <!-- Acciones futuras: editar/eliminar -->
                            <a href="index.php?url=equipos/editar/<?= $equipo['id'] ?>">✏️ Editar</a> | <a href="index.php?url=equipos/confirmar_eliminar/<?= $equipo['id'] ?>"
   onclick="return confirm('¿Estás seguro de que deseas inactivar este equipo?');">
   🗑️ Eliminar
</a>
<a href="index.php?url=equipos/historial/<?= $equipo['id'] ?>">📜 Ver Historial</a>

                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($equipos) === 0): ?>
                    <tr><td colspan="4">No hay equipos registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
