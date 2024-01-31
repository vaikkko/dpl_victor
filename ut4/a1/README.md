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

#### ***PostgreSQL***. <a name="id2"></a>

Instale PostgreSQL tanto en la máquina local (desarrollo) como en la máquina remota (producción) utilizando credenciales distintas.
Cargue los datos de prueba para la aplicación TravelRoad tanto en desarrollo como en producción.
Instale pgAdmin tanto en desarrollo como en producción. Para desarrollo use el dominio pgadmin.local y para producción use el dominio pgadmin.nombrealumno.arkania.es. Utilice credenciales distintas y añada certificado de seguridad en la máquina de producción.
Acceda a pgAdmin y conecte un nuevo servidor TravelRoad con las credenciales aportadas, tanto en desarrollo como en producción.

#### ***Aplicación PHP***. <a name="id3"></a>
Entorno de desarrollo
Instale sudo apt install -y php8.2-pgsql para tener disponible la función pg_connect.
Desarrolle en local una aplicación PHP que se encargue de mostrar los datos de TravelRoad tal y como se ha visto en clase, atacando a la base de datos local.
Utilice control de versiones para alojar la aplicación dentro del repositorio: dpl/ut4/a1
Use el dominio php.travelroad.local para montar la aplicación en el entorno de desarrollo.
Utilice include en su código para incluir el fichero config.php que contendrá los datos de acceso a la base de datos y que no deberá incluirse en el control de versiones.
💡 Incluya en el informe el enlace al código fuente de la aplicación.

Entorno de producción
Clone el repositorio en la máquina de producción.
Incluya el fichero config.php con las credenciales de acceso a la base de datos de producción.
Configure un virtual host en producción para servir la aplicación PHP en el dominio php.travelroad.nombrealumno.arkania.es.
Incluya certificado de seguridad y redirección www.
💡 Incluya en el informe la URL donde está desplegada la aplicación.

Despliegue
Cree un shell-script deploy.sh (con permisos de ejecución) en la carpeta de trabajo del repositorio, que se conecte por ssh a la máquina de producción y ejecute un git pull para actualizar los cambios.
Pruebe este script tras haber realizado algún cambio en la aplicación.
#### ***Desarrollo***. <a name="id4"></a>

# Calculadora con Nginx + PHP-FPM Nativo.

1. Crearemos una carpeta llamada 'Calculadora' en la carpeta "root" de Nginx, dentro colocaremos los archivos necesarios para la ejecución de nuestra app. 

     ![CapturaCarpetaNginx](/ut2/a1/img/CapturaCarpetaNginx.png)

2. Un paso que no es 100% necesario pero puede ayudar en el proceso, es la creación de una clave SSH para conectar el servidor al repositorio Github, así tendremos un mejor acceso a los archivos, mover imágenes más fácilmente, etc.

    ![CapturaRepoSSH](/ut2/a1/img/CapturaRepoSSH.png)

3. Con los permisos necesarios modificados en la instalación previa y estos archivos creados en la carpeta, ejecutaremos en la consola el comando ' sudo nginx -t ' y comprobamos que la sintaxis del fichero de configuración está perfecta.

    Ejecutamos el comando 

             ' sudo systemctl reload nginx.service '
        
    para que todos los archivos nuevos actualicen la carpeta root de Nginx.


4. Por último solo queda comprobar que la app funciona correctamente, escribimos en el navegador: 

         IP + (nombre de la carpeta creada) , en este caso: 
         10.109.25.40/calculadora/ 

    Y como podemos comprobar, funciona tanto el código PHP con su CSS y la imagen que hemos importado del repositorio.

    ![CalculadoraNativaFlecha](/ut2/a1/img/CalculadoraNativaFlecha2.png)

# Calculadora utilizando Docker.

1. Con Docker preinstalado, y todas las configuraciones listas tanto de Nginx como PHP-FPM configuradas correctamente, tendremos una carpeta app en la cual encontraremos ' default.conf ' , ' docker-compose.yml ' y otra carpeta SRC donde tendremos nuestro archivos.

    ![CarpetaApp](/ut2/a1/img/CarpetaAppTree.png)

    - Hacemos uso del Repositorio mediante la clave SSH, para traer los archivos de nuevo y mover el archivo ' .png ' 

     ![pngPcture](/ut2/a1/img/pngPicture.png)

2. A continuación cambiaremos el interior de default.conf para que contenga el archivo ' calculadora.php ' como podemos observar en la siguiente captura de pantalla.

    ![Default.confFlecha](/ut2/a1/img/default.confFlecha.png)

3. Cambiamos el puerto 80 a 90 en el ' docker-cmopose.yml ' ya que en clase el puerto 80 estaba en uso.

    ![90-80](/ut2/a1/img/90-80.png)

4. Tenemos que actualizar como hacíamos con la calculadora nativa mediante:

        ' sudo systemctl reload nginx.service '
        

    Después de esto levantamos el Docker mediante el comando : 
    
         ' sudo docker compose up '

    Y con una vez corriendo el Docker, podemos ir a nuestro navegador y añadir la IP + ':90' ( puerto que hemos cambiado ) + calculadora.php.

    Quedaría:

       ' 10.109.25.40:90/calculadora.php ' 

    
    Aquí podemos ver como quedaría con los comandos utilizados a su derecha:

![CCDockerizado](/ut2/a1/img/CCDockerizado.png)
