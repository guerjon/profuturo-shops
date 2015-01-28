# Profuturo Compras Web

Este proyecto fue desarrollado con [Laravel](http://laravel.com), para su correcto funcionamiento se requiere PHP 5.4+

## Instalación

Una vez que se tiene el código fuente, para el funcionamiento del proyecto en un ambiente local deben seguirse los siguientes pasos:

### Dependecias

Laravel maneja sus dependencias a través de **composer**, es por ello que como primer paso, se instalará composer. Consúltese este [link](https://getcomposer.org/doc/00-intro.md). A lo largo de este documento, asumiremos que se tiene el archivo **composer.phar** en la carpeta de la aplicación. Si se instaló el comando *composer* las instrucciones son muy similares y es fácil interpretarlas de ese modo.

Una vez que se tiene instalado *composer*, ejecutaremos:
```
./composer.phar install
```
Esto nos instalará todas las dependecias de Laravel.

### Configuración ambiente local

Deberemos verificar que Laravel reconoce nuestro equipo como ambiente local, para ello, ejecutaremos el comando
```
./artisan env
```
Lo que debería mostrarnos:
```
Current application environment: local
```
Si este no es el caso, deberemos añadir nuestro *hostname* a la lista de *hosts* locales. Editaremos el archivo **bootstrap/start.php** y ubicaremos estas líneas:
```php
$env = $app->detectEnvironment(array(
  'local' => array('homestead', 'localhost', '127.0.0.1', 'local*', '*local'),
));
```
A este array añadiremos el resultado de ejecutar el comando:
```
hostname
```
Esto debería resolver el problema y nuestro equipo debería ser reconocido como ambiente local.

#### MySQL

Deberemos tener una base de datos MySQL donde se cargará toda la información de la aplicación. No hay una configuración específica, puede ser la que se desee. Sin embargo, esta configuración deberá especificarse en el archivo **app/config/local.php** . El repositorio está configurado para no subir cambios a este archivo.

### Migraciones

Para el correcto funcionamiento de la aplicación, deberemos ejecutar todas las migraciones que generan las tablas en la base de datos. Bastará con ejecutar el comando
```
./artisan migrate --seed
```
Si todo lo anterior fue configurado correctamente, este comando deberá correr sin errores. La bandera `--seed` es utilizada para añadir registros básicos a la base de datos, especificados en el archivo **app/database/seeds/DatabaseSeeder.php**.

## Ejecución

Una vez que se ha llevado a cabo todo el proceso de instalación, podemos utilizar el server de desarrollo de PHP. Para ello, usaremos el comando
```
./artisan serve --port=8080
```
Una vez que se ejecute este proceso, mientras esté activo, podremos acceder a la aplicación a través de http://localhost:8080/

Si se agregaron los registros básicos a la base de datos, podremos acceder con las credenciales como administrador:

* Ccosto : **0**
* Password : **admin**

Podremos acceder también como cualquier otro usuario que esté en la base de datos con su `ccosto` que podemos consultar en la base de datos y una contraseña que podemos consultar en el archivo CSV **app/database/csvs/USUARIOS.csv**
