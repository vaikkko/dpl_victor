<center>

# UT4-A1 Administración de servidores de aplicaciones

</center>

***Víctor Manuel Martínez López:***

***Curso:*** 2º de Ciclo Superior de Desarrollo de Aplicaciones Web.

### ÍNDICE

+ [Objetivos](#id1)
+ [Material empleado](#id2)
+ [Desarrollo](#id3)
+ [Conclusiones](#id4)


#### ***Objetivos***. <a name="id1"></a>

El objetivo de esta tarea es preparar la infraestructura de la capa de datos para el resto de la unidad. En este sentido se va a trabajar con PostgreSQL.

#### ***PostgreSQL*** <a name="id2"></a>

1.  Instale PostgreSQL tanto en la máquina local (desarrollo) como en la máquina remota (producción) utilizando credenciales distintas.
2.  Cargue los datos de prueba para la aplicación TravelRoad tanto en desarrollo como en producción.
3.  Instale pgAdmin tanto en desarrollo como en producción. Para desarrollo use el dominio pgadmin.local y para producción use el dominio pgadmin.nombrealumno.arkania.es. Utilice credenciales distintas y añada certificado de seguridad en la máquina de producción.
4.  Acceda a pgAdmin y conecte un nuevo servidor TravelRoad con las credenciales aportadas, tanto en desarrollo como en producción.

#### ***Aplicación PHP***. <a name="id3"></a>
Entorno de desarrollo

1.  Instale sudo apt install -y php8.2-pgsql para tener disponible la función pg_connect.
2.  Desarrolle en local una aplicación PHP que se encargue de mostrar los datos de TravelRoad tal y como se ha visto en clase, atacando a la base de datos local.
3.  Utilice control de versiones para alojar la aplicación dentro del repositorio: dpl/ut4/a1
4.  Use el dominio php.travelroad.local para montar la aplicación en el entorno de desarrollo.
5.  Utilice include en su código para incluir el fichero config.php que contendrá los datos de acceso a la base de datos y que no deberá incluirse en el control de versiones.

Entorno de producción

1.  Clone el repositorio en la máquina de producción.
2.  Incluya el fichero config.php con las credenciales de acceso a la base de datos de producción.
3.  Configure un virtual host en producción para servir la aplicación PHP en el dominio php.travelroad.nombrealumno.arkania.es.
4.  Incluya certificado de seguridad y redirección www.

Despliegue
 
1.  Cree un shell-script deploy.sh (con permisos de ejecución) en la carpeta de trabajo del repositorio, que se conecte por ssh a la máquina de producción y ejecute un git pull para actualizar los cambios.
2.  Pruebe este script tras haber realizado algún cambio en la aplicación.

#### ***Desarrollo***. <a name="id4"></a>
# - Administración de servidores de aplicaciones -

1. Para la capa de datos de la aplicación que vamos a desplegar, necesitamos un sistema gestor de bases de datos. Trabajaremos sobre PostgreSQL:

    - Lo primero será actualizar los repositorios:
    
            sudo apt update
    - A continuación instalaremos algunos paquetes de soporte:

            sudo apt install -y apt-transport-https
    
    - Descargamos la clave de firma para el repositorio oficial de PostgreSQL:

            curl -fsSL https://www.postgresql.org/media/keys/ACCC4CF8.asc \ 
            | sudo gpg --dearmor -o /etc/apt/trusted.gpg.d/postgresql.gpg
    - Añadimos el repositorio oficial de PostgreSQL al sistema:

            echo "deb http://apt.postgresql.org/pub/repos/apt/ 
            $(lsb_release -cs)-pgdg main" \
            | sudo tee /etc/apt/sources.list.d/postgresql.list > /dev/null

    - Ahora volvemos a actualizar la paquetería:

            sudo apt update 
    -  Instalamos la la versión:
    
            sudo apt install -y postgresql-15

    - Tras la instalación, el servicio PostgreSQL se arrancará automáticamente. Podemos comprobarlo de la siguiente manera:

             sudo systemctl status postgresql
             
        El puerto por defecto en el que trabaja PostgreSQL es el 5432.        

2.  Base de Datos:

    - Vamos a crear una base de datos y un rol de acceso a la misma:

             sudo -u postgres psql

            postgres=# CREATE USER travelroad_user WITH PASSWORD 'dpl0000';
            CREATE ROLE
            postgres=# CREATE DATABASE travelroad WITH OWNER 
            travelroad_user; CREATE DATABASE
            postgres=# \q
        Accedemos al intérprete PostgreSQL con el nuevo usuario:

            psql -h localhost -U travelroad_user travelroad

        Cream la tabla de lugares:

            travelroad=> CREATE TABLE places(
            id SERIAL PRIMARY KEY,
            name VARCHAR(255),
            visited BOOLEAN);
            CREATE TABLE


3. Carga de datos:


     Vamos a cargar los datos desde este fichero places.csv a la tabla places.
    
    - Lo primero será descargar el fichero CSV:

            curl -o /tmp/places.csv https://raw.githubusercontent.com/sdelquin/dpl/main/ut4/files/places.csv
    - A continuación usaremos la función copy de PostgreSQL para insertar los datos en la tabla:

            psql -h localhost -U travelroad_user -d travelroad \
            -c "\copy places(name, visited) FROM '/tmp/places.csv'
            DELIMITER ','"
    - Comprobamos que los datos se han insertado de manera adecuada:

            psql -h localhost -U travelroad_user travelroad


                travelroad=> SELECT * FROM places;
                id |    name    | visited
                ----+------------+---------
                1 | Tokio      | f
                2 | Budapest   | t
                3 | Nairobi    | f
                4 | Berlín     | t
                5 | Lisboa     | t
                6 | Denver     | f
                7 | Moscú      | f
                8 | Oslo       | f
                9 | Río        | t
                10 | Cincinnati | f
                11 | Helsinki   | f
                (11 filas)
   

4. PgAdmin:

 - Creamos las carpetas de trabajo con los permisos adecuados:

        sdelquin@lemon:~$ sudo mkdir /var/lib/pgadmin
        sdelquin@lemon:~$ sudo mkdir /var/log/pgadmin
        sdelquin@lemon:~$ sudo chown $USER /var/lib/pgadmin
        sdelquin@lemon:~$ sudo chown $USER /var/log/pgadmin

 - Creamos un entorno virtual de Python (lo activamos) e instalamos el paquete pgadmin4:

        sdelquin@lemon:~$ cd $HOME
        sdelquin@lemon:~$ python -m venv pgadmin4
        sdelquin@lemon:~$ source pgadmin4/bin/activate

        (pgadmin4) sdelquin@lemon:~$ pip install pgadmin4
        ...
        ...
        ...

 - Ahora lanzamos el script de configuración en el que tendremos que dar credenciales para una cuenta "master":

        pgadmin4
 
 - Servidor en producción:
  - Para poder lanzar el servidor pgAdmin en modo producción y con garantías, necesitaremos hacer uso de un procesador de peticiones WSGI denominado gunicorn.
  Podemos instalarlo como un paquete Python adicional (dentro del entorno virtual):

        pip install gunicorn

 - Ahora ya estamos en disposición de levantar el servidor pgAdmin utilizando gunicorn:

         gunicorn \
        --chdir pgadmin4/lib/python3.11/site-packages/pgadmin4 \
        --bind unix:/tmp/pgadmin4.sock pgAdmin4:app

## Virtualhost en Nginx
- Lo que restaría es crear virtual host en Nginx que sirva la aplicación vía web:

         sudo nano /etc/nginx/conf.d/pgadmin.conf

 - Contenido:

        server {
                server_name pgadmin.arkania.es;

                location / {
                        proxy_pass http://unix:/tmp/pgadmin4.sock;  # socket UNIX
                }
        }

## Demonizando el servicio

- Obviamente no es operativo tener que mantener el proceso gunicorn funcionando en una terminal, por lo que vamos a crear un servicio del sistema.

        sudo nano /etc/systemd/system/pgadmin.service

- Contenido:

        [Unit]
        Description=pgAdmin

        [Service]
        User=sdelquin
        ExecStart=/bin/bash -c '\
        source /home/sdelquin/pgadmin4/bin/activate && \
        gunicorn --chdir /home/sdelquin/pgadmin4/lib/python3.11/site-packages/pgadmin4 \
        --bind unix:/tmp/pgadmin4.sock \
        pgAdmin4:app'
        Restart=always

        [Install]
        WantedBy=multi-user.target

- A continuación recargamos los servicios para luego levantar pgAdmin y  habilitarlo en caso de reinicio del sistema:

        sdelquin@lemon:~$ sudo systemctl daemon-reload
        sdelquin@lemon:~$ sudo systemctl start pgadmin
        sdelquin@lemon:~$ sudo systemctl enable pgadmin

- Por último comprobamos que el servicio está funcionando correctamente:

        sudo systemctl is-active pgadmin
        active

## Registrando un servidor

- Cuando conectamos a pgAdmin tenemos la posibilidad de conectar distintos servidores de bases de datos. Procederemos a registrar la base de datos de TravelRoad.

  - Pulsamos con botón derecho y vamos a Register → Server:

## Acceso externo
  -  Por defecto PostgreSQL sólo permite conexiones desde localhost. Si queremos acceder desde fuera, tendremos que modificar algunas configuraciones.
En primer lugar tendremos que "escuchar" en cualquier IP, no únicamente en localhost (valor por defecto):

        sudo nano /etc/postgresql/15/main/postgresql.conf
- Añadir lo siguiente en la línea 64:

        listen_addresses = '*'

- En segundo lugar tendremos que otorgar permisos. PostgreSQL tiene la capacidad de controlar accesos por:

  - Base de datos.
  - Usuario.
  - IP de origen.

- En este ejemplo vamos a permitir el acceso del usuario travelroad_user a la base de datos travelroad desde cualquier IP de origen:

         sudo nano /etc/postgresql/15/main/pg_hba.conf

- Añadir al final del fichero:

        host travelroad travelroad_user 0.0.0.0/0 md5

- Una vez hechos estos cambios, debemos reiniciar el servicio PostgreSQL para que los cambios surtan efecto:  

         sudo systemctl restart postgresql

Ahora ya podemos acceder a nuestro servidor PostgreSQL desde cualquier máquina utilizando el nombre de dominio/IP del servidor y las credenciales de acceso.

### Instalación del Framework Laravel

![Laravel](/ut4/a1/img/Laravel.jpg)


1. Instalación

- Composer
Lo primero que necesitamos es un gestor de dependencias para PHP. Vamos a instalar Composer:

        curl -fsSL https://raw.githubusercontent.com/composer/getcomposer.org/main/web/installer \
        | php -- --quiet | sudo mv composer.phar /usr/local/bin/composer

- Paquetes de soporte

        sudo apt install -y php8.2-mbstring php8.2-xml \
        php8.2-bcmath php8.2-curl php8.2-pgsql

- Aplicación

Ahora ya podemos crear la estructura de nuestra aplicación Laravel.

        composer create-project laravel/laravel travelroad_laravel

- Por defecto se ha creado un fichero de configuración .env durante el andamiaje. Abrimos este fichero y modificamos ciertos valores para especificar credenciales de acceso:

        pc25-dpl@a109pc25dpl:~/travelroad_laravel$ nano .env

Cambios:

        ...
        APP_NAME=TravelRoad
        APP_ENV=development
        ...
        DB_CONNECTION=pgsql
        DB_HOST=127.0.0.1
        DB_PORT=5432
        DB_DATABASE=travelroad
        DB_USERNAME=travelroad_user
        DB_PASSWORD=dpl0000
        ...

2. Configuración Nginx

- Cambiamos los permisos a los ficheros correspondientes.

        pc25-dpl@a109pc25dpl:~/travelroad$ sudo chgrp -R nginx storage bootstrap/cache
        pc25-dpl@a109pc25dpl:~/travelroad$ sudo chmod -R ug+rwx storage bootstrap/cache

- La configuración del virtual host Nginx para nuestra aplicación Laravel la vamos a hacer en un fichero específico:

        sudo nano /etc/nginx/conf.d/travelroad.conf

Contenido:

        server {
                server_name php.travelroad.local;
                root /usr/share/nginx/travelroad_laravel;

                index index.html index.htm index.php;

                location / {
                        try_files $uri $uri/ /index.php?$query_string;
                }

                location ~ \.php$ {
                        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
                        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
                        include fastcgi_params;
                }
        }

## Ahora modificaremos la aplicación:

- Lo primero es cambiar el código de la ruta:

        pc25-dpl@a109pc25dpl:~/travelroad$ vi routes/web.php
Contenido:

        <?php

        // https://laravel.com/api/6.x/Illuminate/Support/Facades/DB.html
        use Illuminate\Support\Facades\DB;

        Route::get('/', function () {
        $wished = DB::select('select * from places where visited = false');
        $visited = DB::select('select * from places where visited = true');

        return view('travelroad', ['wished' => $wished, 'visited' => $visited]);
        });

- Ahora cambiar la plantilla:

        pc25-dpl@a109pc25dpl:~/travelroad$ vi resources/views/travelroad.blade.php
Contenido:

        <html>
        <head>
        <title>Travel List</title>
        </head>

        <body>
        <h1>My Travel Bucket List</h1>
        <h2>Places I'd Like to Visit</h2>
        <ul>
        @foreach ($wished as $place)
        <li>{{ $place->name }}</li>
        @endforeach
        </ul>

        <h2>Places I've Already Been To</h2>
        <ul>
        @foreach ($visited as $place)
        <li>{{ $place->name }}</li>
        @endforeach
        </ul>
        </body>
        </html>

- Con esto tendríamos la plicación funcionando en local.

Resultado:

![LaravelLocal](/ut4/a1/img/LaravelLocal.png)


### Producción

1. Clonamos el repositorio de la maquina local y procedemos  ejecutar el siguiente comando, que tendrá todas las dependencias del proyecto.

        pc25-dpl@a109pc25dpl:~/travelroad$ composer install

2. Creamos otro virtual host con el server_name que nos pide la actividad:

![travelroad_laravelConf](/ut4/a1/img/travelroad_laravelConf.png)


3. Le añadimos el certificado de encriptación mediante Certbo, y lo modificamos un poco para que aunque accedamos al server nam sin " www " nos redirija a la correcta.

![CertbotLaravel](/ut4/a1/img/CertbotLaravel.png)

4. Para finalizar, creamos un script para que al ejecutarlo todos los cambios que hagamos en local, se suban al repositorio y se haga un pull  a la maquina de producción.

![deployLaravel](/ut4/a1/img/deployLaravel.png)

## Resultado en Producción

![MyTravelBucketList_Laravel](/ut4/a1/img/MyTravelBucketList_Laravel.png)

