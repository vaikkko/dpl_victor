
### Instalación del Framework Express

![ExpresLogo](/ut4/a2/img/ExpressLogo.png)

### Intalación:

1. Lo primero que debemos instalar es Node.js: un entorno de ejecución para JavaScript construido con V8, motor de JavaScript de Chrome.

Existe un instalador que nos facilita añadir los repositorios oficiales de Node.js. El comando a ejecutar es el siguiente:

        curl -fsSL https://deb.nodesource.com/setup_current.x | sudo -E bash

2. Ahora ya podemos instalar Node.js de forma ordinaria:

         sudo apt install -y nodejs

### Aplicación

- Ahora ya podemos crear la estructura (andamiaje) de nuestra aplicación Express. Para ello utilizamos express-generator una herramienta que debemos instalar de forma global en el sistema:

        sudo npm install -g express-generator

- Creamos la estructura base de la aplicación indicando que las vistas (plantillas) van a utilizar pug como motor de plantillas:

        express --view=pug travelroad

- El comando anterior creará una carpeta travelroad con la estructura base para poder desarrollar nuestra aplicación web:

- Ahora debemos instalar las dependencias:

        npm install

    npm install obtiene las dependencias del fichero package.json y almacena los paquetes en la carpeta node_modules.

- Ahora podemos probar la aplicación lanzando el servidor de desarrollo:

        pc25-dpl@a109pc25dpl:/usr/share/nginx/travelroad_express$ DEBUG=travelroad:* npm start


## Configuración de la base de datos

- Para poder acceder a la base de datos PostgreSQL necesitamos una dependencia adicional node-postgres. Realizamos la instalación:

        npm install pg

- Siempre es interesante guardar las credenciales en un fichero "externo". En este caso vamos a trabajar con un fichero .env con lo que necesitaremos el paquete dotenv. Lo instalamos:

         npm install dotenv

- En este fichero hay que guardar la cadena de conexión a la base de datos PostgreSQL:

         pc25-dpl@a109pc25dpl:/usr/share/nginx/travelroad_express$ echo 'PSQL_CONNECTION=postgresql://travelroad_user:dpl0000@localhost:5432/travelroad' > .env

### Lógica de negocio

- Nos queda modificar el comportamiento de la aplicación para cargar los datos y mostrarlos en una plantilla.

        pc25-dpl@a109pc25dpl:/usr/share/nginx/travelroad_express$ mkdir config && vi config/database.js

- Gestión de las rutas:

    ![Expres](/ut4/a2/img/RoutesExpress.png)
<br>

- Plantillas para las vistas:

    ![Expres](/ut4/a2/img/indexExpress.png)

    ![ExpresLogo](/ut4/a2/img/visitedExpress.png)

    ![ExpresLogo](/ut4/a2/img/wishedExpress.png)

### Gestionando procesos 

Vamos a hacer uso de pm2 un gestor de procesos para aplicaciones Node.js en producción.

- Lo primero es instalar el paquete de forma global en el sistema:

        sudo npm install -g pm2

- Ahora ya podemos lanzar un proceso en background con nuestra aplicación. Estando en la carpeta de trabajo ~/travelroad ejecutamos el siguiente comando:

        pm2 start ./bin/www --name travelroad

- Configuramos un virtual host en Nginx

    ![ExpresLogo](/ut4/a2/img/vhostLocal.png)


### Script de despliegue

![ExpresLogo](/ut4/a2/img/deployExpress.png)


### Producción


1. Clonamos el repositorio de la maquina local y procedemos a installar todas las dependencias necesarias y creamos el Virtual host, aplicando el Cerbot:

![ExpresLogo](/ut4/a2/img/vhostArkaniaExpress.png)

## Resultado en Producción

![ExpresLogo](/ut4/a2/img/FinalExpress.png)
![ExpresLogo](/ut4/a2/img/visitedExpressVista.png)
![ExpresLogo](/ut4/a2/img/wishedExpressVista.png)



