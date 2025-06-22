<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Auditoría de Equipos</title>
    <link rel="stylesheet" href="public/css/estilos.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .filters input,
        .filters select,
        .filters button {
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            justify-content: flex-start;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            color: white;
            background-color: #007bff;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-print {
            background-color: #28a745;
        }

        .btn-export {
            background-color: #6c757d;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Auditoría de Cambios en Equipos</h2>

    <div class="filters">
        <input type="text" id="buscador" placeholder="Escribe parte del nombre..." onkeyup="filtrarTabla()">
        <select id="comboEquipos" onchange="filtrarTabla()">
            <option value="">-- Ver todos los equipos --</option>
            <?php foreach ($equiposUnicos as $nombre): ?>
                <option value="<?= htmlspecialchars($nombre) ?>"><?= htmlspecialchars($nombre) ?></option>
            <?php endforeach; ?>
        </select>
    </div>



    <div class="actions">
        <button class="btn btn-print" onclick="window.print()">🖨️ Imprimir</button>
        <a href="index.php?url=equipos/exportar_pdf" class="btn btn-export">📄 Exportar PDF</a>
        <a href="index.php?url=equipos/exportar_excel" class="btn btn-export">📊 Exportar Excel</a>
    </div>



    
    <table id="tablaAuditoria">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Equipo</th>
                <th>Estado</th>
                <th>Motivo</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($historial as $registro): ?>
                <tr>
                    <td><?= htmlspecialchars($registro['fecha']) ?></td>
                    <td><?= htmlspecialchars($registro['equipo']) ?></td>
                    <td><?= htmlspecialchars($registro['accion']) ?></td>
                    <td><?= htmlspecialchars($registro['motivo']) ?></td>
                    <td><?= htmlspecialchars($registro['usuario']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    <a href="index.php?url=equipos/lista">← Volver a Equipos</a>
</div>

<script>
    function filtrarTabla() {
        const inputTexto = document.getElementById("buscador").value.toLowerCase();
        const comboBox = document.getElementById("comboEquipos").value.toLowerCase();
        const tabla = document.getElementById("tablaAuditoria").getElementsByTagName("tbody")[0];
        const filas = tabla.getElementsByTagName("tr");

        for (let fila of filas) {
            const nombreEquipo = fila.cells[1].textContent.toLowerCase();
            const coincideTexto = nombreEquipo.includes(inputTexto);
            const coincideCombo = comboBox === "" || nombreEquipo === comboBox;

            fila.style.display = (coincideTexto && coincideCombo) ? "" : "none";
        }
    }
</script>
</body>
</html>
