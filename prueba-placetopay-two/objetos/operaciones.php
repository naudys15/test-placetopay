<?php
namespace Objetos;

use JC\Cache\SimpleCache as Cache;

abstract class Operaciones
{
    const BASICO = 1;
    const MIXTO = 2;
    const RECURRENTE = 3;
    const SUSCRIPCION = 4;
    const IP_ADDRESS = '127.0.0.1';
    const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36';

    public $datos;

    public function getDatos()
    {
        return $this->datos;
    }

    function setRequest()
    {
        $reference = $this->datos->getReference();
        $request = [
            'payment' => [
                'reference' => $reference,
                'description' => $this->datos->getDescription(),
                'amount' => [
                    'currency' => $this->datos->getCurrency(),
                    'total' => $this->datos->getTotal(),
                ],
            ],
            'expiration' => date('c', strtotime('+2 days')),
            'returnUrl' => $_SERVER['HTTP_REFERER'].'?response='.$reference,
            'ipAddress' => Operaciones::IP_ADDRESS,
            'userAgent' => Operaciones::USER_AGENT,
        ];
        return $request;
    }
}

abstract class Almacenamiento
{
    const BD = 1;
    const CACHE = 2;

    public $datos;

    public function almacenarBD()
    {
        $conn = new \mysqli("localhost", "root", "", "place2pay");
        if (mysqli_connect_errno()) {
            printf("Falló la conexión: %s\n", mysqli_connect_error());
            exit();
        }
        $status	= mysqli_real_escape_string($conn, (strip_tags($this->datos->status(), ENT_QUOTES)));
        $razon = mysqli_real_escape_string($conn, (strip_tags($this->datos->reason(), ENT_QUOTES)));
        $mensaje = mysqli_real_escape_string($conn, (strip_tags($this->datos->message(), ENT_QUOTES)));
        $fecha = date("Y-m-d H:i:s", strtotime($this->datos->date()));
        $requestId = $_SESSION['requestId'];
        $insert = mysqli_query($conn, 'INSERT INTO RESPONSE VALUES (0, "'.$status.'", "'.$razon.'", "'.$mensaje.'", "'.$fecha.'", '.$requestId.', "'.$fecha.'", "'.$fecha.'")');
    }
    
    public function almacenarCache()
    {
        Cache::add('status', $this->datos->status(), 3600);
        Cache::add('reason', $this->datos->reason(), 3600);
        Cache::add('message', $this->datos->message(), 3600);
        Cache::add('date', date("Y-m-d H:i:s", strtotime($this->datos->date())), 3600);
        Cache::add('requestId', $_SESSION['requestId'], 3600);
    }

    public function obtenerCache()
    {
        if (Cache::exists('status')) {
            $response = array(
                'status' => (Cache::fetch('status') == 'APPROVED')?'OK':'FALLO',
                'message' => Cache::fetch('message'),
                'date' => Cache::fetch('date')
            );
        } else {
            $response = null;
        }
        return $response;
    }
}

class OperacionBasica extends Operaciones
{
    public function __construct($datos)
    {
		$this->datos = $datos;
    }
}

class OperacionMixta extends Operaciones
{
    public function __construct($datos)
    {
		$this->datos = $datos;
    }
}

class OperacionRecurrente extends Operaciones
{
    public function __construct($datos)
    {
		$this->datos = $datos;
    }
}

class OperacionSuscripcion extends Operaciones
{
    public function __construct($datos)
    {
		$this->datos = $datos;
    }
}

class AlmacenamientoBD extends Almacenamiento
{
    public function __construct($datos)
    {
		$this->datos = $datos;
    }
}

class AlmacenamientoCache extends Almacenamiento
{
    public function __construct($datos)
    {
		$this->datos = $datos;
    }
}

class ObtenerBD extends Almacenamiento
{
    public function __construct()
    {
    }
}

class ObtenerCache extends Almacenamiento
{
    public function __construct()
    {
    }
}