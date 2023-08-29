<?php


namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);
                if($usuario) {
                    // Verificar el password
                    if( $usuario->comprobarPasswordAndVerificado($auth->password) ){
                        // Autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        }else {
                            header('Location: /cita');
                        }
                        
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }
        
        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }
    public static function logout() {
        if (!$_SESSION) {
            session_start();// El if porque si coloco session_start(); arroja un error notice:
        }        
        $_SESSION = [];
        header('Location: /');
    }

    public static function olvide(Router $router) {        
        $alertas = [];
                
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);
                
                if($usuario && $usuario->confirmado === "1") {
                    
                    // Generar un Token
                    $usuario->crearToken();
                    $usuario->guardar();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de Éxito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                   
                }else {
                    Usuario::setAlerta('error', 'El usuario NO Existe o No está Confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }
    
    # Recuperar Password
    public static function recuperar(Router $router) {
        
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);// Recordar que s() es la función que hemos creado para sanitizar los datos que ingresarán a la base

        // Buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
           Usuario::setAlerta('error', 'Token No Válido');
           $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo password y guardarlo

            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)) {
                $usuario->password = null;           
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = ''; 

                $resultado = $usuario->guardar();
                // if($resultado) {
                //     header('Location: /');
                // }
                if($resultado) {
                    // Crear mensaje de exito
                    Usuario::setAlerta('exito', 'Password Actualizado Correctamente');
                                    
                    // Redireccionar al login tras 3 segundos
                    header('Refresh: 7; url=/');
                }

            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {
        $usuario = new Usuario;
        
        //Alertas vacías
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            
            // Revisar que alerta esté vacío
            if(empty($alertas)) {
                // Verificar que el usuario no esté registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                }else {
                    // Hashear el password
                    $usuario->hashPassword();

                    // Generar un token único
                    $usuario->crearToken();

                    // Enviar el E-Mail
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmación();

                    // Crear el usuario
                    $resultado = $usuario->guardar();
                    
                    if($resultado) {
                        header('Location: /mensaje');
                    }
                    //debuguear($usuario);
                }
            }       
        }
        
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);// Con $router->render('', []) mandamos a llamar una función a una vista
    }

    public static function mensaje(Router $router) {

        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {
        $alertas = [];
        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);
        
        if(empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token No Válido');
        }else{
            // Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = "";
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        // Obtener Alertas
        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }

}