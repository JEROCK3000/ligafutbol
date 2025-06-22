<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Core\Controller;
use PDO;

class EquiposController extends Controller
{
    private $db;

    public function __construct()
    {
        require_once '../app/Config/database.php';
        $this->db = $pdo;
    }

    public function lista()
    {
        $stmt = $this->db->query("SELECT e.*, u.nombre AS entrenador FROM equipos e
                              LEFT JOIN usuarios u ON e.entrenador_id = u.id
                              WHERE e.activo = 1");
        $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('equipos/lista', ['equipos' => $equipos]);
    }

    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);

            if (!empty($nombre)) {
                $stmt = $this->db->prepare("INSERT INTO equipos (nombre) VALUES (:nombre)");
                $stmt->execute(['nombre' => $nombre]);
                header('Location: index.php?url=equipos/lista');
                exit;
            } else {
                $error = "El nombre del equipo es obligatorio.";
                $this->view('equipos/crear', ['error' => $error]);
                return;
            }
        }

        $this->view('equipos/crear');
    }

    public function editar($id)
    {
        // Verifica si el equipo existe
        $stmt = $this->db->prepare("SELECT * FROM equipos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $equipo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$equipo) {
            echo "Equipo no encontrado.";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);

            if (!empty($nombre)) {
                $update = $this->db->prepare("UPDATE equipos SET nombre = :nombre WHERE id = :id");
                $update->execute(['nombre' => $nombre, 'id' => $id]);
                header('Location: index.php?url=equipos/lista');
                exit;
            } else {
                $error = "El nombre del equipo no puede estar vacío.";
                $this->view('equipos/editar', ['equipo' => $equipo, 'error' => $error]);
                return;
            }
        }

        $this->view('equipos/editar', ['equipo' => $equipo]);
    }

    public function eliminar($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $motivo = trim($_POST['motivo']);

            // Inactivar equipo
            $stmt = $this->db->prepare("UPDATE equipos SET activo = 0, motivo_estado = :motivo WHERE id = :id");
            $stmt->execute(['motivo' => $motivo, 'id' => $id]);

            // Registrar en historial
            $usuario_id = $_SESSION['usuario']['id'];

            $historial = $this->db->prepare("INSERT INTO historial_equipos (equipo_id, accion, motivo, usuario_id) 
                                 VALUES (:equipo_id, 'deshabilitado', :motivo, :usuario_id)");
            $historial->execute([
                'equipo_id' => $id,
                'motivo' => $motivo,
                'usuario_id' => $usuario_id
            ]);
        }

        header('Location: index.php?url=equipos/lista');
        exit;
    }


    public function deshabilitados()
    {
        $stmt = $this->db->query("SELECT e.*, u.nombre AS entrenador FROM equipos e
                              LEFT JOIN usuarios u ON e.entrenador_id = u.id
                              WHERE e.activo = 0");
        $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('equipos/deshabilitados', ['equipos' => $equipos]);
    }

    public function habilitar($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $motivo = trim($_POST['motivo']);

            // Reactivar equipo
            $stmt = $this->db->prepare("UPDATE equipos SET activo = 1, motivo_estado = :motivo WHERE id = :id");
            $stmt->execute(['motivo' => $motivo, 'id' => $id]);

            // Registrar en historial
            $usuario_id = $_SESSION['usuario']['id'];

            $historial = $this->db->prepare("INSERT INTO historial_equipos (equipo_id, accion, motivo, usuario_id) 
                                 VALUES (:equipo_id, 'deshabilitado', :motivo, :usuario_id)");
            $historial->execute([
                'equipo_id' => $id,
                'motivo' => $motivo,
                'usuario_id' => $usuario_id
            ]);
        }

        header('Location: index.php?url=equipos/deshabilitados');
        exit;
    }

    public function confirmar_eliminar($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM equipos WHERE id = :id AND activo = 1");
        $stmt->execute(['id' => $id]);
        $equipo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$equipo) {
            echo "Equipo no encontrado o ya deshabilitado.";
            return;
        }

        $this->view('equipos/confirmar_eliminar', ['equipo' => $equipo]);
    }

    public function confirmar_habilitar($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM equipos WHERE id = :id AND activo = 0");
        $stmt->execute(['id' => $id]);
        $equipo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$equipo) {
            echo "Equipo no encontrado o ya activo.";
            return;
        }

        $this->view('equipos/confirmar_habilitar', ['equipo' => $equipo]);
    }

    public function historial($id)
    {
        // Trae el historial
        $stmt = $this->db->prepare("SELECT * FROM historial_equipos WHERE equipo_id = :id ORDER BY fecha DESC");
        $stmt->execute(['id' => $id]);
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Trae el nombre del equipo
        $stmt2 = $this->db->prepare("SELECT nombre FROM equipos WHERE id = :id");
        $stmt2->execute(['id' => $id]);
        $equipo = $stmt2->fetch(PDO::FETCH_ASSOC);

        $this->view('equipos/historial', [
            'historial' => $registros,
            'equipo' => $equipo
        ]);
    }

    /*public function auditoria()
{
    // Consulta historial con nombre del equipo y del usuario
    $stmt = $this->db->query("SELECT h.fecha, e.nombre AS equipo, h.accion, h.motivo, 
                                     COALESCE(u.nombre, 'No registrado') AS usuario 
                              FROM historial_equipos h 
                              JOIN equipos e ON h.equipo_id = e.id 
                              LEFT JOIN usuarios u ON h.usuario_id = u.id 
                              ORDER BY h.fecha DESC");
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener lista única de nombres de equipos para el filtro ComboBox
    $equiposUnicos = array_unique(array_column($historial, 'equipo'));

    // Enviar datos a la vista
    require_once '../app/Views/equipos/auditoria.php';
}*/

public function auditoria()
{
    // Consulta historial con nombre del equipo y del usuario
    $stmt = $this->db->query("SELECT h.fecha, e.nombre AS equipo, h.accion, h.motivo, 
                                     COALESCE(u.nombre, 'No registrado') AS usuario 
                              FROM historial_equipos h 
                              JOIN equipos e ON h.equipo_id = e.id 
                              LEFT JOIN usuarios u ON h.usuario_id = u.id 
                              ORDER BY h.fecha DESC");
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Lista única de equipos para ComboBox
    $equiposUnicos = array_unique(array_column($historial, 'equipo'));
    sort($equiposUnicos); // orden alfabético

    // Vista
    require_once '../app/Views/equipos/auditoria.php';
}



    public function filtrarAuditoria()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre']);

        $stmt = $this->db->prepare("SELECT h.fecha, e.nombre AS equipo, h.accion, h.motivo, 
                                    COALESCE(u.nombre, 'No registrado') AS usuario 
                                    FROM historial_equipos h 
                                    JOIN equipos e ON h.equipo_id = e.id 
                                    LEFT JOIN usuarios u ON h.usuario_id = u.id
                                    WHERE e.nombre LIKE :nombre
                                    ORDER BY h.fecha DESC");
        $stmt->execute(['nombre' => '%' . $nombre . '%']);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($resultados);
    }
}

public function exportar_pdf()
{
    require_once '../vendor/autoload.php';

    $stmt = $this->db->query("SELECT h.fecha, e.nombre AS equipo, h.accion, h.motivo, 
                                     COALESCE(u.nombre, 'No registrado') AS usuario 
                              FROM historial_equipos h 
                              JOIN equipos e ON h.equipo_id = e.id 
                              LEFT JOIN usuarios u ON h.usuario_id = u.id 
                              ORDER BY h.fecha DESC");
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = '<h2 style="text-align:center;">Auditoría de Cambios en Equipos</h2><table border="1" width="100%" cellpadding="5" cellspacing="0">';
    $html .= '<thead><tr>
        <th>Fecha</th><th>Equipo</th><th>Acción</th><th>Motivo</th><th>Usuario</th>
        </tr></thead><tbody>';
    foreach ($historial as $fila) {
        $html .= '<tr>';
        $html .= "<td>{$fila['fecha']}</td><td>{$fila['equipo']}</td><td>{$fila['accion']}</td><td>{$fila['motivo']}</td><td>{$fila['usuario']}</td>";
        $html .= '</tr>';
    }
    $html .= '</tbody></table>';

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('auditoria_equipos.pdf', 'D');
}


public function exportar_excel()
{
    require_once __DIR__ . '/../../vendor/autoload.php';
    
    // Consulta auditoría
    $stmt = $this->db->query("SELECT h.fecha, e.nombre AS equipo, h.accion, h.motivo,
                                      COALESCE(u.nombre, 'No registrado') AS usuario 
                               FROM historial_equipos h
                               JOIN equipos e ON h.equipo_id = e.id
                               LEFT JOIN usuarios u ON h.usuario_id = u.id
                               ORDER BY h.fecha DESC");
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Crear Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Ajustar automáticamente el ancho de las columnas A a E
    foreach (range('A', 'E') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Encabezados
    $sheet->fromArray(['Fecha', 'Equipo', 'Acción', 'Motivo', 'Usuario'], NULL, 'A1');

    // Contenido
    $row = 2;
    foreach ($historial as $registro) {
        $sheet->setCellValue("A{$row}", $registro['fecha']);
        $sheet->setCellValue("B{$row}", $registro['equipo']);
        $sheet->setCellValue("C{$row}", $registro['accion']);
        $sheet->setCellValue("D{$row}", $registro['motivo']);
        $sheet->setCellValue("E{$row}", $registro['usuario']);
        $row++;
    }

    // Encabezados para descarga
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="auditoria_equipos.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}




}
