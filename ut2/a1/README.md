<center>

# UT2-A1: Implantación de arquitecturas web
## Aplicación PHP
</center>

***Víctor Manuel Martínez López:***

***Curso:*** 2º de Ciclo Superior de Desarrollo de Aplicaciones Web.

### ÍNDICE

+ [Introducción](#id1)
+ [Objetivos](#id2)
+ [Material empleado](#id3)
+ [Desarrollo](#id4)
+ [Conclusiones](#id5)


#### ***Introducción***. <a name="id1"></a>

En esta práctica crearemos una aplicación con PHP que funcione como calculadora usando Nginx con PHP-FPM y Docker.


#### ***Objetivos***. <a name="id2"></a>

1. Utilizar una interfaz similar a la siguiente:

![CalculadoraEj](/ut2/a1/img/Calculadora.png)

2. Incluir esta imagen de la calculadora que se adjunta.
3. Incluir un fichero .css con unos estilos básicos.
4. La "calculadora nativa" debe tener como título h1 "Calculadora en entorno nativo" y la "calculadora dockerizada" debe tener como título h1 "Calculadora en entorno dockerizado".
5. Trabajar en una carpeta dentro del $HOME


#### ***Material empleado***. <a name="id3"></a>

Para realizar esta práctica tendremos una maquina virtual que en este caso será Debian 12 como servidor, con Nginx, PHP-FPM y Docker instalados y nos conectaremos a ella mediante SSH desde un cliente.

Necesitamos , tener bien instalado y configurado los programas nombrados anteriormente, cambiar la información de los archivos para establecer los permisos oportunos para cada configuración.

Una vez realizados estos pasos, podremos empezar a preparar nuestra app.

#### ***Desarrollo***. <a name="id4"></a>

1. Primer paso: 

    - Crearemos una carpeta llamada 'Calculadora' en la carpeta "root" de Nginx, dentro colocaremos los archivos necesarios para la ejecución de nuestra app. 

        ![CapturaCarpetaNginx](/ut2/a1/img/CapturaCarpetaNginx.png)


    - Un paso que no es 100% necesario pero puede ayudar en el proceso, es la creación de una clave SSH para conectar el servidor al repositorio Github, así tendremos un mejor acceso a los archivos, mover imagenes mas facilmente, etc.

    ![CapturaRepoSSH](/ut2/a1/img/CapturaRepoSSH.png)

    - Con los permisos necesarios modificados en la instalación previa y estos archivos creados en la carpeta, ejecutaremos en la consola el comando ' sudo nginx -t ' y comprobamos que la sintaxis del fichero de configuración está perfecta.

        Ejecutamos el comando ' sudo systemctl reload nginx.service ' para que todos los archivos nuevos actualicen la carpeta root de Nginx.

    - Por ultimo solo queda comprobar que la app funcióna correctamente y se ve en el navegador, escribimos : 

         IP + (nombre de la carpeta creada) , en este caso: 
         10.109.25.40/calculadora/ 

          Y como podemos comprobar funciona tanto el código PHP con su CSS y la imagen que hemos importado del repositorio.

        ![CalculadoraNativaFlecha](/ut2/a1/img/CalculadoraNativaFlecha.png)

> ***IMPORTANTE:*** si estamos capturando una terminal no hace falta capturar todo el escritorio y es importante que se vea el nombre de usuario.

Si encontramos dificultades a la hora de realizar algún paso debemos explicar esas dificultades, que pasos hemos seguido para resolverla y los resultados obtenidos.

#### ***Conclusiones***. <a name="id5"></a>

En esta parte debemos exponer las conclusiones que sacamos del desarrollo de la prácica.
