<?php

namespace Model;

class AdminCita extends ActiveRecord {// Extiendo a ActiveRecord porque voy a necesitar conectar a la BD
    // Base de datos
    protected static $tabla = 'citasservicios';
    protected static $columnasDB = ['id', 'hora', 'cliente', 'email', 'telefono', 'servicio', 'precio'];// recordar que cliente es un alias de las columnas concatenadas de nombre y apellido así como servicio es el alias de la columna nombre en la tabla servicios

    public $id;
    public $hora;
    public $cliente;
    public $email;
    public $telefono;
    public $servicio;
    public $precio;

    public function __construct()
    {
        $this->id = $args['id'] ?? null;// ?? significa que si no está presente ese dato se lo completa con null
        $this->hora = $args['hora'] ?? '';
        $this->cliente = $args['cliente'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->servicio = $args['servicio'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }


}