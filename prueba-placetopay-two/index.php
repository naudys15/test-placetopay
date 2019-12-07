<?php
session_start();
require 'vendor/autoload.php';
require_once '/objetos/autenticacion.php';
require_once '/objetos/factory.php';
require_once '/objetos/operaciones.php';
require_once '/objetos/pago.php';

use Objetos\Autenticacion;
use Objetos\Pago;
use Objetos\OperacionesFactory;
use Objetos\Operaciones;
use Objetos\Almacenamiento;
use Dnetix\Redirection\PlacetoPay;

function setResponseMessage($status, $message)
{
    if ($status == 'FAILED' || $status == 'REJECTED') {
        $_SESSION['status'] = 'FALLO';
    } elseif ($status == 'APPROVED') {
        $_SESSION['status'] = 'OK';
    }
    $_SESSION['message'] = $message;
}

function clearResponseMessage()
{
    $_SESSION['status'] = '';
    $_SESSION['message'] = '';
}

if (isset($_POST) && $_POST != []) {
    $auth = new Autenticacion();
    $payment = new Pago($_POST['reference'], $_POST['description'], $_POST['currency'], $_POST['total']);

    $placetopay = new PlacetoPay([
        'login' => $auth::LOGIN_ID,
        'tranKey' => $auth::SECRET_KEY,
        'url' => $auth::URL_PLACETOPAY
    ]);

    $transaccion = OperacionesFactory::crearOperacion(Operaciones::BASICO, $payment);
    $request = $transaccion->setRequest();

    $response = $placetopay->request($request);
    if ($response->isSuccessful()) {
        $_SESSION['requestId'] =  $response->requestId;
        $_SESSION['processUrl'] = $response->processUrl;
        header('Location: '.$response->processUrl);
    } else {
        setResponseMessage($response->status()->status(), $response->status()->message());
    }
} else {
    if (isset($_GET) && $_GET != []) {
        $auth = new Autenticacion();
        $placetopay = new PlacetoPay([
            'login' => $auth::LOGIN_ID,
            'tranKey' => $auth::SECRET_KEY,
            'url' => $auth::URL_PLACETOPAY
        ]);
        $response = $placetopay->query($_SESSION['requestId']);
        if ($response->isSuccessful()) {
            $transaccion = OperacionesFactory::almacenarInformacion(Almacenamiento::BD, $response->status());
            $transaccion->almacenarBD();
        }
        $transaccion->almacenarCache();

        setResponseMessage($response->status()->status(), $response->status()->message());
    }
}
$transaccion = OperacionesFactory::obtenerInformacion(Almacenamiento::CACHE);
$respuestaCache = [];
$respuestaCache = $transaccion->obtenerCache();
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Prueba Place2Pay Paso 2</title>

        <!-- Styles -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="content text-center m-auto">
                <div class="row">
                    <img class="m-auto" src="imagenes/logo.png" width="50%" height="100">
                </div>
                <?php 
                if (isset($_SESSION['status']) && $_SESSION['status'] == 'OK') { 
                ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?=$_SESSION['message'];?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php
                } elseif (isset($_SESSION['status']) && $_SESSION['status'] == 'FALLO') {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?=$_SESSION['message'];?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php
                }
                ?>
                <br>
                <form method="POST" action="#">
                    <div class="form-group">
                        <input class="form-control" type="text" name="name" placeholder="Nombre" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="email" name="email" placeholder="Correo Electrónico" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="reference" placeholder="Referencia" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="description" placeholder="Descripción" required>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-9">
                                <input class="form-control" type="number" name="total" min="0" step="0.01" placeholder="Monto" required>
                            </div>
                            <div class="col-3">
                                <input class="form-control" type="text" name="currency" readonly placeholder="COP" value="COP">
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-success" type="submit" name="pay">Enviar</button>
                    </div>      
                </form>
                <?php
                if (isset($respuestaCache) && $respuestaCache != null) {
                ?>
                    <div><strong>ÚLTIMA OPERACIÓN</strong></div><br>
                    <div class="d-flex justify-content-center">
                        <table class="d-table justify-content-center table table-responsive">
                            <thead>
                                <th>ESTADO</th>
                                <th>MENSAJE</th>
                                <th>FECHA</th>
                            </thead>
                            <tbody>
                                <td><?=$respuestaCache['status'];?></td>
                                <td><?=$respuestaCache['message'];?></td>
                                <td><?=$respuestaCache['date'];?></td>
                            </tbody>
                        </table>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </body>
</html>
<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<?php
clearResponseMessage();
