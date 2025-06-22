<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Liga de Fútbol</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

    <div class="contenedor">
        <h1>Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?> 👋</h1>
        <p class="rol">Rol: <?php echo $usuario['rol']; ?></p>

        <div class="menu">
            <a href="index.php?url=equipos/lista" class="opcion">⚽ Equipos</a>
            <a href="index.php?url=equipos/auditoria" class="opcion">🕵️ Auditoría de Equipos</a>
            <a href="index.php?url=jugadores/lista" class="opcion">👥 Jugadores</a>
            <a href="index.php?url=partidos/lista" class="opcion">📅 Partidos</a>
            <a href="index.php?url=tabla_posiciones" class="opcion">📊 Tabla de Posiciones</a>
            <a href="index.php?url=reportes" class="opcion">📑 Reportes</a>
            <a href="index.php?url=usuarios/logout" class="opcion salir">🔒 Cerrar sesión</a>
        </div>
    </div>

</body>
</html>
