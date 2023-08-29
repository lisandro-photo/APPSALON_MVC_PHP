<?php

namespace Controllers;

use MVC\Router;


class CitaController {
    public static function index( Router $router) {
        //session_start(); Si lo inicio arroja un error notice:
        if (!$_SESSION['nombre']) {
            session_start();
          }

        isAuth();
        
        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id']
        ]);
    }
}