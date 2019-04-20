<?php
namespace App\Helpers;

class EstadoTransaccion
{
    public $existeError                 = false;
    //public $existeRegistro            = false;
    public $mensaje                     = "";
    public $data;
    public static $noExistenDatos       = "No existen datos con el criterio seleccionado";
    public static $procesoExitoso       = "Proceso ejecutado exitosamente";
    public static $procesoErroneo       = "Hubo un error, comuníquese con su administrador de sistemas";
    public static $registroYaExiste     = "No se puede crear, registro ya existe";
    public static $operacionNoPermitida = 'Operación no permitida';

    function __construct() 
    {
        //$this->data = new data();
    }
}

class data
{
    public $filas;
}
