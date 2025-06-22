<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial del Equipo</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

<div class="contenedor">
    <h2>Historial de cambios: <?= htmlspecialchars($equipo['nombre']) ?></h2>
    <a href="index.php?url=equipos/lista" class="opcion">← Volver al listado</a>

    <table class="tabla">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Acción</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($historial)) : ?>
                <?php foreach ($historial as $registro): ?>
                    <tr>
                        <td><?= htmlspecialchars($registro['fecha']) ?></td>
                        <td><?= ucfirst(htmlspecialchars($registro['accion'])) ?></td>
                        <td><?= nl2br(htmlspecialchars($registro['motivo'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr><td colspan="3">Sin registros.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
