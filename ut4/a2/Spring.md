![Spring](/ut4/a2/img/SpringLogo.png)

### Instalación del Framework Spring

Spring es un framework de código abierto que da soporte para el desarrollo de aplicaciones y páginas webs basadas en Java. Se trata de uno de los entornos más populares y ayuda a crear aplicaciones con un alto rendimiento empleando objetos de Java sencillos.

![Spring](/ut4/a2/img/vhostArkaniaExpress.png)


## Instalación

### JDK
- Lo primero será instalar el Java Development Kit (JDK). Existe una versión "opensource" denominada OpenJDK.

Descargamos el paquete OpenJDK desde su página de descargas:

        curl -O --output-dir /tmp \
        https://download.java.net/java/GA/jdk19.0.1/afdd2e245b014143b62ccb916125e3ce/10/GPL/openjdk-19.0.1_linux-x64_bin.tar.gz

- Ahora descomprimimos el contenido del paquete en /usr/lib/jvm:

        sudo tar -xzvf /tmp/openjdk-19.0.1_linux-x64_bin.tar.gz \
        --one-top-level=/usr/lib/jvm

- Necesitamos realizar alguna configuración adicional para que el JDK funcione correctamente.
Por un lado establecer variables de entorno adecuadas a la instalación. Básicamente indicar dónde se encuentran los ejecutables de Java modificando la variable de entorno PATH:

        sudo vi /etc/profile.d/jdk_home.sh

Contenido:

        #!/bin/sh
        export JAVA_HOME=/usr/lib/jvm/jdk-19.0.1/
        export PATH=$JAVA_HOME/bin:$PATH

Por otro lado actualizar las alternativas para los ejecutables:

         pc25-dpl@a109pc25dpl:$ sudo update-alternatives --install \
        "/usr/bin/java" "java" "/usr/lib/jvm/jdk-19.0.1/bin/java" 0

        pc25-dpl@a109pc25dpl:$ sudo update-alternatives --install \
        "/usr/bin/javac" "javac" "/usr/lib/jvm/jdk-19.0.1/bin/javac" 0

        pc25-dpl@a109pc25dpl:$ sudo update-alternatives --set java \
        /usr/lib/jvm/jdk-19.0.1/bin/java

        pc25-dpl@a109pc25dpl:$ sudo update-alternatives --set javac \
        /usr/lib/jvm/jdk-19.0.1/bin/javac

### SDKMAN

- SDKMAN es una herramienta que permite gestionar versiones de kits de desarrollo de software (entre ellos Java). Para su instalación debemos comprobar que tenemos el paquete zip instalado en el sistema:

        sudo apt install -y zip

- Ahora ejecutamos el siguiente script de instalación:

         curl -s https://get.sdkman.io | bash

- A continuación activamos el punto de entrada de la siguiente manera:

        source "$HOME/.sdkman/bin/sdkman-init.sh"

### Spring Boot
- Dentro de Spring, existe un subproyecto denominado Spring Boot que facilita la preparación de aplicaciones Spring para ponerlas en producción. Utilizaremos esta herramienta durante el despliegue.

        sdk install springboot

### Maven

- Maven es una herramienta enfocada a la construcción de proyectos Java que permite la gestión de dependencias. Su instalación también es a través de SDKMAN:

        sdk install maven


## Creación del proyecto

- Creamos la estructura base del proyecto utilizando la herramienta en línea de comandos para Spring Boot:

        spring init \
        --build=maven \
        --dependencies=web \
        --group=edu.dpl \
        --name=travelroad \
        --description=TravelRoad \
        travelroad

- Listamos el contenido de la carpeta de trabajo:

        pc25-dpl@a109pc25dpl:$ tree travelroad
        travelroad
        ├── HELP.md
        ├── mvnw
        ├── mvnw.cmd
        ├── pom.xml
        └── src
            ├── main
            │   ├── java
            │   │   └── edu
            │   │       └── dpl
            │   │           └── travelroad
            │   │               └── TravelroadApplication.java
            │   └── resources
            │       ├── application.properties
            │       ├── static
            │       └── templates
            └── test
                └── java
                    └── edu
                        └── dpl
                            └── travelroad
                                └── TravelroadApplicationTests.java

        14 directories, 7 files

### Escritura de código
- Tendremos que adaptar un poco la estructura inicial del proyecto para cumplir con el objetivo de la aplicación que tenemos que desarrollar.

- Dentro de la carpeta src/main tendremos que organizar los distintos módulos (controlador, modelo y plantilla) que componen la aplicación de la siguiente manera:

        pc25-dpl@a109pc25dpl:~/travelroad$ mkdir -p src/main/java/edu/dpl/travelroad/controllers
        pc25-dpl@a109pc25dpl:~/travelroad$  touch src/main/java/edu/dpl/travelroad/controllers/HomeController.java

        pc25-dpl@a109pc25dpl:~/travelroad$ mkdir -p src/main/java/edu/dpl/travelroad/models
        pc25-dpl@a109pc25dpl:~/travelroad$  touch src/main/java/edu/dpl/travelroad/models/Place.java

        pc25-dpl@a109pc25dpl:~/travelroad$  mkdir -p src/main/java/edu/dpl/travelroad/repositories
        pc25-dpl@a109pc25dpl:~/travelroad$  touch src/main/java/edu/dpl/travelroad/repositories/PlaceRepository.java

        pc25-dpl@a109pc25dpl:~/travelroad$  touch src/main/resources/templates/home.html

- Tras esto, la estructura del proyecto quedaría tal que así:

        pc25-dpl@a109pc25dpl:~/travelroad$ tree src/main
        src/main
        ├── java
        │   └── edu
        │       └── dpl
        │           └── travelroad
        │               ├── controllers
        │               │   └── HomeController.java
        │               ├── models
        │               │   └── Place.java
        │               ├── repositories
        │               │   └── PlaceRepository.java
        │               └── TravelroadApplication.java
        └── resources
            ├── application.properties
            ├── static
            └── templates
                └── home.html

        10 directories, 6 files

### Controlador

        vi src/main/java/edu/dpl/travelroad/controllers/HomeController.java

Contenido:

        package edu.dpl.travelroad.controllers;

        import edu.dpl.travelroad.models.Place;
        import edu.dpl.travelroad.repositories.PlaceRepository;
        import org.springframework.beans.factory.annotation.Autowired;
        import org.springframework.stereotype.Controller;
        import org.springframework.ui.Model;
        import org.springframework.web.bind.annotation.GetMapping;

        @Controller
        public class HomeController {
            private final PlaceRepository placeRepository;

            @Autowired
            public HomeController(PlaceRepository placeRepository) {
                this.placeRepository = placeRepository;
            }

            @GetMapping("/")
            public String home(Model model) {
                model.addAttribute("wished", placeRepository.findByVisited(false));
                model.addAttribute("visited", placeRepository.findByVisited(true));
                return "home";  // home.html
            }
        }

#### Modelos

        vi src/main/java/edu/dpl/travelroad/models/Place.java

Contenido:

        package edu.dpl.travelroad.models;

        import javax.persistence.Entity;
        import javax.persistence.GeneratedValue;
        import javax.persistence.GenerationType;
        import javax.persistence.Id;
        import javax.persistence.Table;

        @Entity
        @Table(name = "places")
        public class Place {

            @Id
            @GeneratedValue(strategy = GenerationType.AUTO)
            private Long id;

            private String name;
            private Boolean visited;

            public Place() {
            }

            public Place(Long id, String name, Boolean visited) {

                this.id = id;
                this.name = name;
                this.visited = visited;
            }

            public Long getId() {
                return id;
            }

            public String getName() {
                return name;
            }

            public void setName(String name) {
                this.name = name;
            }

            public Boolean getVisited() {
                return visited;
            }

            public void setVisited(Boolean visited) {
                this.visited = visited;
            }
        }

### Repositorio

        vi src/main/java/edu/dpl/travelroad/repositories/PlaceRepository.java

Contenido:

        package edu.dpl.travelroad.repositories;

        import edu.dpl.travelroad.models.Place;

        import java.util.List;
        import org.springframework.data.repository.CrudRepository;
        import org.springframework.stereotype.Repository;
        import org.springframework.data.jpa.repository.Query;

        @Repository
        public interface PlaceRepository extends CrudRepository<Place, Long> {

            @Query("SELECT p FROM Place p WHERE p.visited = ?1")
            List<Place> findByVisited(Boolean visited);
        }

## Plantilla

        vi src/main/resources/templates/home.html

Contenido:

        <!DOCTYPE HTML>
        <html>
        <head>
            <title>My Travel Bucket List</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        </head>
        <body>
            <h1>My Travel Bucket List</h1>
            <p><a th:href="@{/wished}">Places I'd like to visit</a></p>
            <p><a th:href="@{/visited}">Places I've already been to</a></p>
            <p>Powered by Spring</p>
        </body>
        </html>



## Dependencias:

        vi pom.xml

Contenido:

        <?xml version="1.0" encoding="UTF-8"?>
        <project xmlns="http://maven.apache.org/POM/4.0.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://maven.apache.org/POM/4.0.0 https://maven.apache.org/xsd/maven-4.0.0.xsd">
            <modelVersion>4.0.0</modelVersion>
            <parent>
                <groupId>org.springframework.boot</groupId>
                <artifactId>spring-boot-starter-parent</artifactId>
                <version>2.7.5</version>
                <relativePath/> <!-- lookup parent from repository -->
            </parent>
            <groupId>edu.dpl</groupId>
            <artifactId>travelroad</artifactId>
            <version>0.0.1-SNAPSHOT</version>
            <name>travelroad</name>
            <description>TravelRoad</description>
            <properties>
                <java.version>19</java.version>
            </properties>
            <dependencies>
                <dependency>
                    <groupId>org.springframework.boot</groupId>
                    <artifactId>spring-boot-starter-web</artifactId>
                </dependency>

                <dependency>
                    <groupId>org.springframework.boot</groupId>
                    <artifactId>spring-boot-starter-test</artifactId>
                    <scope>test</scope>
                </dependency>

                <dependency>
                    <groupId>org.springframework.boot</groupId>
                    <artifactId>spring-boot-starter-thymeleaf</artifactId>
                </dependency>

                <dependency>
                    <groupId>org.springframework.boot</groupId>
                    <artifactId>spring-boot-starter-data-jpa</artifactId>
                </dependency>

                <dependency>
                <groupId>org.postgresql</groupId>
                <artifactId>postgresql</artifactId>
                <scope>runtime</scope>
                </dependency>
            </dependencies>

            <build>
                <plugins>
                    <plugin>
                        <groupId>org.springframework.boot</groupId>
                        <artifactId>spring-boot-maven-plugin</artifactId>
                    </plugin>
                </plugins>
            </build>

        </project>


### Credenciales:

        vi src/main/resources/application.properties

Contenido: 

        spring.datasource.url=jdbc:postgresql://localhost:5432/travelroad
        spring.datasource.username=travelroad_user
        spring.datasource.password=dpl0000

### Proceso de construcción
- Para poner en funcionamiento el proyecto necesitamos dos fases que se llevarán a cabo mediante Maven:

    - Compilación.
    - Empaquetado.

La herramienta que usamos para ello es Maven, pero en el propio andamiaje de la aplicación ya se incluye un Maven Wrapper denominado mvnw que incluye todo lo necesario para poder construir el proyecto.

- Para llevar a cabo la compilación del proyecto ejecutamos lo siguiente:

        ./mvnw compile
- Para llevar a cabo el empaquetado del proyecto ejecutamos lo siguiente:

        ./mvnw package
- Tras esto, obtendremos un archivo JAR (Java ARchive) en la ruta:

        ls target/travelroad-0.0.1-SNAPSHOT.jar
        target/travelroad-0.0.1-SNAPSHOT.jar

- Una forma de lanzar la aplicación es correr el fichero JAR generado:

         java -jar target/travelroad-0.0.1-SNAPSHOT.jar


## Entorno de producción

- De cara a simplificar el proceso de despliegue en el entorno de producción, podemos disponer de un script que realice los pasos del proceso de construcción:

        vi run.sh

Contenido:

        #!/bin/bash

        cd $(dirname $0)

        ./mvnw package  # el empaquetado ya incluye la compilación

        # ↓ Último fichero JAR generado
        JAR=`ls target/*.jar -t | head -1`
        /usr/bin/java -jar $JAR

Asignamos permisos de ejecución:

         chmod +x run.sh

- A continuación creamos un fichero de servicio (de usuario) para gestionarlo mediante systemd:

        pc25-dpl@a109pc25dpl:$ mkdir -p ~/.config/systemd/user
        pc25-dpl@a109pc25dpl:$ vi ~/.config/systemd/user/travelroad.service

Contenido: 

        [Unit]
        Description=Spring Boot TravelRoad

        [Service]
        Type=simple
        StandardOutput=journal
        ExecStart=/home/sdelquin/travelroad/run.sh

        [Install]
        WantedBy=default.target

- Habilitamos el servicio para que se arranque automáticamente:

        systemctl --user enable travelroad.service
        Created symlink /home/sdelquin/.config/systemd/user/default.target.wants/travelroad.service → /home/sdelquin/.config/systemd/user/travelroad.service.
- Iniciamos el servicio para comprobar su funcionamiento:

        systemctl --user start travelroad.service


## Configuración final local:

- Creamos el virtual host en local:

                server {
            server_name travelroad;

            location / {
                proxy_pass http://localhost:8080;  # socket TCP
            }
        }

- Script:

![Spring](/ut4/a2/img/ScriptSpring.png)


## Configuración final local:

1. Clonamos el repositorio de la maquina local y procedemos a installar todas las dependencias necesarias y creamos el Virtual host, aplicando el Cerbot:

![Spring](/ut4/a2/img/vhostSpringArkania.png)

## Resultado en Producción

![Spring](/ut4/a2/img/VistaHomeSpring.png)




