<?php
namespace Objetos;

use Objetos\Operaciones;
use Objetos\Almacenamiento;

class OperacionesFactory
{
    public function __construct()
    {

    }

    public static function crearOperacion($tipoOperacion, $datos)
    {
		switch ($tipoOperacion) {
			case Operaciones::BASICO:
				return new OperacionBasica($datos);
                break;
            case Operaciones::MIXTO:
				return new OperacionMixta($datos);
                break;
            case Operaciones::RECURRENTE:
				return new OperacionRecurrente($datos);
                break;
            case Operaciones::SUSCRIPCION:
				return new OperacionSuscripcion($datos);
				break;
		}
    }
    
    public static function almacenarInformacion($tipoAlmacenamiento, $datos)
    {
        switch ($tipoAlmacenamiento) {
			case Almacenamiento::BD:
				return new AlmacenamientoBD($datos);
                break;
            case Almacenamiento::CACHE:
				return new AlmacenamientoCache($datos);
                break;
		}
    }

    public static function obtenerInformacion($tipoAlmacenamiento)
    {
        switch ($tipoAlmacenamiento) {
			case Almacenamiento::BD:
				return new ObtenerBD();
                break;
            case Almacenamiento::CACHE:
				return new ObtenerCache();
                break;
		}
    }
}