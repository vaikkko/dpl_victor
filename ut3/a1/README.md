<center>

# UT3-A1: Administración de servidores web

##

</center>

**_Víctor Manuel Martínez López:_**

**_Curso:_** 2º de Ciclo Superior de Desarrollo de Aplicaciones Web.

### ÍNDICE

- [Introducción](#id1)
- [Objetivos](#id2)
- [Material empleado](#id3)
- [Desarrollo](#id4)
- [Conclusiones](#id5)

#### **_Introducción_**. <a name="id1"></a>

En esta tarea deberemos desplegar una aplicación web escrita en HTML/Javascript que permita hacer uso del módulo de Nginx ngx_small_light.

Este módulo sirve para generar "miniaturas" de imágenes on the fly además de otros posibles procesamientos a través de peticiones URL.

#### **_Objetivos_**. <a name="id2"></a>

1. Instalar el módulo ngx_small_light y cargarlo dinámicamente en Nginx.
2. Crear un virtual host específico que atienda peticiones en el dominio images.nombrealumno.me (server_name).
3. Habilitar el módulo ngx_small_light en el virtual host sólo para el location /img.
4. Subir las imágenes de images.zip (el archivo de adjunta a la tarea ) a una carpeta img dentro de la carpeta de trabajo elegida.
5. Crear una aplicación web que permita el tratamiento de dichas imágenes.
6. Incorporar certificado de seguridad (mostrar el certificado 🔒).
7. Redirigir el subdominio www al dominio base (incluyendo ssl)

#### **_Material empleado_**. <a name="id3"></a>

Para realizar esta práctica tendremos una máquina virtual que en este caso será Debian 12 como servidor, con Nginx y Docker instalados y nos conectaremos a ella mediante SSH desde un cliente.

Necesitamos , tener bien instalado y configurado los programas nombrados anteriormente, cambiar la información de los archivos para establecer los permisos oportunos para cada configuración.

Una vez realizados estos pasos, podremos empezar a preparar nuestra app.

#### **_Desarrollo_**. <a name="id4"></a>

# Calculadora con Nginx + PHP-FPM Nativo.

1.  Crearemos una carpeta llamada 'Calculadora' en la carpeta "root" de Nginx, dentro colocaremos los archivos necesarios para la ejecución de nuestra app.

    ![CapturaCarpetaNginx](/ut2/a1/img/CapturaCarpetaNginx.png)

2.  Un paso que no es 100% necesario pero puede ayudar en el proceso, es la creación de una clave SSH para conectar el servidor al repositorio Github, así tendremos un mejor acceso a los archivos, mover imágenes más fácilmente, etc.

    ![CapturaRepoSSH](/ut2/a1/img/CapturaRepoSSH.png)

3.  Con los permisos necesarios modificados en la instalación previa y estos archivos creados en la carpeta, ejecutaremos en la consola el comando ' sudo nginx -t ' y comprobamos que la sintaxis del fichero de configuración está perfecta.

    Ejecutamos el comando

             ' sudo systemctl reload nginx.service '

    para que todos los archivos nuevos actualicen la carpeta root de Nginx.

4.  Por último solo queda comprobar que la app funciona correctamente, escribimos en el navegador:

         IP + (nombre de la carpeta creada) , en este caso:
         10.109.25.40/calculadora/

    Y como podemos comprobar, funciona tanto el código PHP con su CSS y la imagen que hemos importado del repositorio.

    ![CalculadoraNativaFlecha](/ut2/a1/img/CalculadoraNativaFlecha2.png)

# Calculadora utilizando Docker.

1.  Con Docker preinstalado, y todas las configuraciones listas tanto de Nginx como PHP-FPM configuradas correctamente, tendremos una carpeta app en la cual encontraremos ' default.conf ' , ' docker-compose.yml ' y otra carpeta SRC donde tendremos nuestro archivos.

    ![CarpetaApp](/ut2/a1/img/CarpetaAppTree.png)

    - Hacemos uso del Repositorio mediante la clave SSH, para traer los archivos de nuevo y mover el archivo ' .png '

    ![pngPcture](/ut2/a1/img/pngPicture.png)

2.  A continuación cambiaremos el interior de default.conf para que contenga el archivo ' calculadora.php ' como podemos observar en la siguiente captura de pantalla.

    ![Default.confFlecha](/ut2/a1/img/default.confFlecha.png)

3.  Cambiamos el puerto 80 a 90 en el ' docker-cmopose.yml ' ya que en clase el puerto 80 estaba en uso.

    ![90-80](/ut2/a1/img/90-80.png)

4.  Tenemos que actualizar como hacíamos con la calculadora nativa mediante:

        ' sudo systemctl reload nginx.service '

    Después de esto levantamos el Docker mediante el comando :

         ' sudo docker compose up '

    Y con una vez corriendo el Docker, podemos ir a nuestro navegador y añadir la IP + ':90' ( puerto que hemos cambiado ) + calculadora.php.

    Quedaría:

    ' 10.109.25.40:90/calculadora.php '

    Aquí podemos ver como quedaría con los comandos utilizados a su derecha:

![CCDockerizado](/ut2/a1/img/CCDockerizado.png)

#### **_Conclusiones_**. <a name="id5"></a>

- A mi parecer ambas opciones son útiles, ya que la configuración nativa proporciona mayor control sobre el sistema y puede ser más eficiente, pero a su vez la opción dockerizada facilita mucho la gestión y la escalabilidad.

  Dependerá de la necesidad de cada proyecto el uso de una u otra.
