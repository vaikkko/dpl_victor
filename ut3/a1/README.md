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

# Aplicación Web - Manejo de imágenes

1. Instalamos el módulo ngx_small_light, descargando el código fuente.

    ![InstalciónSLight](/ut3/a1/img/InstalciónSLight.png)

2.  Una vez instalado necesitaremos configurar la compilación. 

    ![Configure](/ut3/a1/img/Configure.png)

3.  Ahora tendremos que generar la librería dinámica:

    
    ![MakeModules](/ut3/a1/img/MakeModules.png)


4.  A continuación, vemos el archivo 'ngx_htp_small_light_module.so' en la carpeta 'objts', este fichero se crea del proceso que hemos realizado anteriormente, tendremos que moverlo a la carpeta 'modules' de nginx, que es la carpeta donde se cargan los módulos dinámicos de Nginx.

    ![objs](/ut3/a1/img/objs.png)

    ![copy.so](/ut3/a1/img/copy.so.png)

5. Para terminar con la configuración, modificaremos el archivo 'nginx.conf' y le añadiremos la línea :

        'load_module /etc/nginx/modules/ngx_http_small_light_module.so;'
    para que el módulo se cargue como corresponde.

    ![nginxConf](/ut3/a1/img/nginxConf.png)


         
# Creación y configuración del virtual host:

1.  Primero he creado una carpeta con los archivos e imágenes necesarias para que la apliciación funcione correctamente y la he añadido a la carpeta "root" de Nginx. 

    ![appImages](/ut3/a1/img/appImages.png)

   
2.  Por ultimo he creado un host virtual llamado 'appImages.conf', con el 'server_name' que pide la actividad 'images.victor.me' , le he señalado la carpeta root que tiene que seguir y luego he especificado la ubicación (location) de la carpeta /img , añadiendo las directivas del módulo utilizado, en este caso small_light. 

    ![appImagesConf2](/ut3/a1/img/appImagesConf2.png)

# Resultado final :

- Dependiendo de los parametros que le indiques, manipula las imágenes de una manera u otra:
<br>


 - Página inicial:

![web0](/ut3/a1/img/web0.png)


 - Página con datos1:

![web1](/ut3/a1/img/web1.png)


 - Página con datos2:

![web1.1](/ut3/a1/img/web1.1.png)



#### **_Conclusiones_**. <a name="id5"></a>

- La utilización de host virtuales y módulos puede ser realmente útil para que el rendimiento y la experiencia de uso sea más fluida, ampliando las funcionalidades del servidor de una manera más efectiva, siendo esto importante a la hora de desarrollar una web.
