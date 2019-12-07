# Test for PlaceToPay

En este repositorio se encuentra la solución de la prueba planteada por ustedes.
Fue realizada por Luis Contreras el día 26-4-19
Contiene una única rama, la master tiene el paso 1 de la prueba desarrollada.
En una carpeta llamada prueba-placetopay-two se encuentra realizado el paso 2. Esta carpeta puede ser extraida y compilada como un proyecto independiente sin ningun problema.
La estructura de código fue desarrollada usando los estándares PSR-1, PSR-2 y PSR-4.
En el siguiente repositorio se encuentra la resolución de la prueba planteada por ustedes.

# Funcionamiento de la aplicación (Paso 1)

NOTA: ESTE PASO FUE REALIZADO USANDO EL FRAMEWORK LARAVEL Y EL PATRON DE DISEÑO MVC
Se tiene un formulario que recolecta la información necesaria para hacer funcionar la api.
Una vez insertados los mismos, se ejecuta la función sendRequest del controlador RequestToPay, la cual realiza una petición de acuerdo a los datos de autenticación aportados por ustedes. Si la petición es satisfactoria, se procede a la redirección a la pasarela de pago, en caso contrario se devuelve a la página principal indicando el error.
Se procede en la pasarela de pago a insertar los datos de pago necesarios, y la respuesta del pago es devuelta al sitio, la cual es recibida por la función del controlador receiveRequest.
Dependiendo de la respuesta, se almacena en base de datos y en cache la respuesta.
NOTA: En caché se almacena por un lapso de 60 minutos.
Esta respuesta almacenada en cache puede ser visualizada en el sitio.

# Funcionamiento de la aplicación (Paso 2)

NOTA: ESTE PASO FUE REALIZADO USANDO PHP NATIVO Y EL PATRON DE DISEÑO FACTORY
Esta versión de la prueba consta de una solo archivo php llamado index, el cual es el encargado de procesar tanto la solicitud como la respuesta de la api.
Para el proceso de operacion (Básica, Mixta, Recurrente y Suscripción) y almacenamiento (BD y Cache), se creó una clase llamada OperacionesFactory, la cual permite crear una nueva operación, almacenar la información recibida de la pasarela y mostrar la información. Esta clase actua como una fábrica de objetos dependiendo de la operación a realizar, los objetos que se pueden crear de esta fábrica son: OperacionBasica, OperacionMixta, OperacionRecurrente, OperaciónSuscripción, AlmacenamientoBD, AlmacenamientoCache, ObtenerBD y ObtenerCache. Estos objetos heredan de clases abstractas llamadas Operaciones y Almacenamiento.
Se hace uso de dos librerías, la desarrollada por dnetix para el manejo de la conexión, y la creada por jaredchu llamada simple-cache para el manejo de memoria cache.
Esta última se puede descargar por medio de composer con el comando:
composer require jaredchu/simple-cache
Se hace uso de composer para facilitar el uso de librerías.
La estructura de funcionamiento es similar a la del paso 1. Su diseño es muy similar.

# Pruebas

Las pruebas unitarias escogidas cumplen las siguientes funciones:

Se verifica que cargue correctamente el home del sitio.
Se verifica que se envíe el formulario correctamente
Se verifica que el método de respuesta cargue correctamente