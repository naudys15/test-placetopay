<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{!! csrf_token() !!}" />

        <title>Prueba Place2Pay Paso 1</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <!-- Styles -->
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    </head>
    <body>
        <div class="container text-center">
            <div class="row">
                <img class="m-auto" src="{{asset('imagenes/logo.png')}}" width="50%" height="100">
            </div>
            @if (Session::has('status') && Session::get('status') == 'OK')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{Session::get('message')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @elseif (Session::has('status') && Session::get('status') == 'FALLO')
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{Session::get('message')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <br>
            <form method="POST" action="{{route('send')}}">
                @csrf
                <div class="form-group">
                    <input class="form-control" type="text" name="name" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <input class="form-control" type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" name="reference" placeholder="Reference" required>
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" name="description" placeholder="Description" required>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-9">
                            <input class="form-control" type="number" name="total" min="0" step="0.01" placeholder="Amount" required>
                        </div>
                        <div class="col-3">
                            <input class="form-control" type="text" name="currency" readonly placeholder="COP" value="COP">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-success" type="submit" name="pay">Send</button>
                </div>            
            </form>
            @if (isset($cache) && $cache != null)
                <div><strong>ÚLTIMA OPERACIÓN</strong></div><br>
                <div class="d-flex justify-content-center">
                    <table class="d-table justify-content-center table table-responsive">
                        <thead>
                            <th>ESTADO</th>
                            <th>MENSAJE</th>
                            <th>FECHA</th>
                        </thead>
                        <tbody>
                            <td>{{$cache['status']}}</td>
                            <td>{{$cache['message']}}</td>
                            <td>{{$cache['date']}}</td>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </body>
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</html>

