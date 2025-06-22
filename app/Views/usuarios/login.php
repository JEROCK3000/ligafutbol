<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Liga de Fútbol</title>
</head>
<body>
    <h2>Iniciar sesión</h2>

    <?php if (!empty($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="correo">Correo:</label>
        <input type="email" name="correo" required><br><br>

        <label for="clave">Contraseña:</label>
        <input type="password" name="clave" required><br><br>

        <button type="submit">Ingresar</button>
    </form>
</body>
</html>
