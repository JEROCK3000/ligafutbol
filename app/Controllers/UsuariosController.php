<?php
namespace App\Controllers;

use Core\Controller;
use PDO;

class UsuariosController extends Controller
{
    private $db;

    public function __construct()
    {
        require_once '../app/Config/database.php';
        $this->db = $pdo; // viene del archivo database.php
    }

    public function login()
    {
        // Si ya hay sesión, redirige
        if (isset($_SESSION['usuario'])) {
            header('Location: index.php?url=usuarios/dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'] ?? '';
            $clave = $_POST['clave'] ?? '';

            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE correo = :correo AND activo = 1");
            $stmt->execute(['correo' => $correo]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($clave, $usuario['clave'])) {
                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'nombre' => $usuario['nombre'],
                    'rol' => $usuario['rol']
                ];
                header('Location: index.php?url=usuarios/dashboard');
                exit;
            } else {
                $error = "Credenciales incorrectas.";
                $this->view('usuarios/login', ['error' => $error]);
                return;
            }
        }

        $this->view('usuarios/login');
    }

    public function dashboard()
    {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?url=usuarios/login');
            exit;
        }

        $this->view('usuarios/dashboard', ['usuario' => $_SESSION['usuario']]);
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php?url=usuarios/login');
    }
}
