<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;
use Twilio\Rest\Client;

class APIController {
    public static function index() {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar() {
       
        // Almacena la cita y devuelve el Id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado['id'];

        // $respuesta = [
        //     'cita' => $cita
        // ];

        // Almacena los Servicios con el Id de la cita
        $idServicios = explode(",", $_POST['servicios']);
        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();

        }

        // Retornamos una respuesta
        echo json_encode(['resultado' => $resultado]);

    }

    // public static function enviarResumen() {
    //     // Your Account SID and Auth Token from twilio.com/console
    //     $sid = 'AC5d68b5be6af0fbc7bb04b2831bb363ea';
    //     $token = '01446d3cc0ac59e2d2ed35cfdb5ebc30';
    //     $client = new Client($sid, $token);

    //     // Use the client to do fun stuff like send text messages!
    //     $client->messages->create(
    //     // the number you'd like to send the message to
    //     '$this->telefono',
    //         [
    //         // A Twilio phone number you purchased at twilio.com/console
    //         'from' => '+573003767796',
    //         // the body of the text message you'd like to send
    //         'body' => 'class="seccion contenido-resumen"'
    //         ]
    //     );
    // }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}