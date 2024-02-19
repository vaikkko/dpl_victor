
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

        pc25-dpl@a109pc25dpl:~/travelroad_laravel$ sudo chgrp -R nginx storage bootstrap/cache
        pc25-dpl@a109pc25dpl:~/travelroad_laravel$ sudo chmod -R ug+rwx storage bootstrap/cache

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

        pc25-dpl@a109pc25dpl:~/travelroad_laravel$ nano routes/web.php
Contenido:

![webPHP](/ut4/a2/img/webPHP.png)
        

- Ahora añadir las plantillas:

        pc25-dpl@a109pc25dpl:~/travelroad_laravel$ nano resources/views/travelroad.blade.php
Contenido de la vista principal:

![Vistars](/ut4/a2/img/ViewsPaginaPrincial.png)

Contenido de la vista de sitios a visitar:

![Vistars](/ut4/a2/img/ViewsWished.png)

Contenido de la vista de sitios visitados:

![Vistars](/ut4/a2/img/ViewsVisited.png)


- Con esto tendríamos la plicación funcionando en local.


### Producción

1. Clonamos el repositorio de la maquina local y procedemos  ejecutar el siguiente comando, que tendrá todas las dependencias del proyecto.

        pc25-dpl@a109pc25dpl:~/travelroad_laravel$ composer install

2. Creamos otro virtual host con el server_name que nos pide la actividad:

![travelroad_laravelConf](/ut4/a1/img/travelroad_laravelConf.png)


3. Le añadimos el certificado de encriptación mediante Certbot, y lo modificamos para que nos redirija a la correcta.

![CertbotLaravel](/ut4/a1/img/CertbotLaravel.png)

4. Para finalizar, creamos un script para que al ejecutarlo todos los cambios que hagamos en local, se suban al repositorio y se haga un pull  a la maquina de producción.

![deployLaravel](/ut4/a1/img/deployLaravel.png)

## Resultado en Producción

![MyTravelBucketList_Laravel](/ut4/a2/img/PaginaFinalLaravel.png)
![MyTravelBucketList_Laravel](/ut4/a2/img/WishedLaravel.png)
![MyTravelBucketList_Laravel](/ut4/a2/img/VisitedLaravel.png)
