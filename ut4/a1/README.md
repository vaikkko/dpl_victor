<center>

# UT4-A1 Administración de servidores de aplicaciones
<<<<<<< HEAD

=======
>>>>>>> 7aba798c7ad84484382f214294981f6e78a8ef83
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
   
    ![CalculadoraNativaFlecha](/ut2/a1/img/CalculadoraNativaFlecha2.png)

# Calculadora utilizando Docker.