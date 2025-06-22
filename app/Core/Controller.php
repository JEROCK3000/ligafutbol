<?php
namespace Core;

class Controller
{
    public function view($vista, $datos = [])
    {
        extract($datos);
        require_once "../app/Views/$vista.php";
    }
}
