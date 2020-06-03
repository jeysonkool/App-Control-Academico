# App Control Academico

Sistema de Control Académico para Colegios Privados y Públicos, Desarrollado para Unidades Educativas del Estado Colombiano, es un sistema bastante versatíl y dinámico, Diseño tanto para Plataformas de Escritorio mediante el navegador, como también para dispositivos móviles (Tablet, Móviles) adecuandonse el sistema a la resolución del dispositivo de manera correcta y eficiente.

## Integrantes del proyecto
* Jeyson Escalona
* Amrando

## Recomendaciones

Se recomienda utilizar cualquier distribución Linux y usar docker como se explica a continuación. En la brevedad armaremos la documentación para trabajar con windows.

# Instalación del App-Control-Academico usando docker


## Instalación de docker-compose

Una vez que docker se encuentra instalado en la PC, se debe instalar
docker-compose como se indica en la [documentación oficial](https://docs.docker.com/compose/install/).

# Instalación en producción

El siguiente ejemplo, muestra como iniciar el producto considerando que ya
dispone de los pre-requisitos instalados en su sistema.

## Docker compose para producción

Cree una carpeta con el nombre del proyecto _-por ejemplo el nombre de su
colegio_ y dentro de esta carpeta cree un archivo `docker-compose.yml` con el
siguiente contenido:

```yml
version: '2'
volumes:
  db:
  disciplinary-sanction-documents:
  justification-documents:
  persons-photos:
services:
  app:
    image: registry.gitlab.com/jeysonkool/Control-academico:latest
    environment:
      DB_HOST: db
      DB_NAME: kimkelen
      DB_PASSWORD: poli2020
      DB_USERNAME: politecnico
      DEBUG: 'false'
      FLAVOR: demo
      MEMCACHE_HOST: memcache
      MEMCACHE_PORT: '11211'
      TESTING: 'true'
      MAIL_PORT: 25
      MAIL_HOST: localhost
      FACEBOOK_ID: 'demo'
      FACEBOOK_SECRET= 'demo'
    ports:
    - 80:80
    volumes:
    - disciplinary-sanction-documents:/app/data/disciplinary-sanction-documents
    - justification-documents:/app/data/justification-documents
    - persons-photos:/app/data/persons-photos
  memcache:
    image: memcached:1.4
    command:
    - -m
    - '256'
  db:
    image: mysql:5.6
    environment:
      MYSQL_DATABASE: colegios
      MYSQL_ROOT_PASSWORD: poli2020
    volumes:
    - db:/var/lib/mysql
```

Una vez creado, correr el siguiente comando:

```
docker-compose up
```

### Notas

Debe considerar editar las variables de ambiente que permiten modificar su
instalación:

* `TESTING:` si el valor es true, muestra una leyenda que indica que es una versión de prueba
* `FLAVOR:` configura el flavor de esta instalación de kimkelen. Por defecto se
  asume **demo**.
* `DEBUG:` configura el producto para trabajar en modo dev, esto es, se muestra
  la barra de symfony y los errores con más detalle. Útil para detectar
  problemas.

## Trabajando en desarrollo

Primero es necesario clonar este repositorio:

```
git clone git@github.com:jeysonkool/Control-academico.git
```

> Si va a realizar cambios, se recomienda que forkee el repositorio en GitHub y
> utilice un repositorio personal para manejar sus personalizaciones bajo un
> sistema de control de versiones.

### Configuraciones basadas en variables de ambiente

La modalidad de trabajo con docker impulsa un uso de variables de ambiente para
las configuraciones de los contenedores. Es por ello, que toda la
parametrización del producto se realiza a través de variables de ambiente.

Para trabajar durante el desarrollo, se recomienda entonces usar [direnv](https://direnv.net/)
para lograr ciertas abstracciones que simplifican la labor sin pensar en que se
está trabajando usando docker.

Direnv, es un producto que al ingresar a un directorio (y cualquier
subdirectorio por debajo de un padre) que contenga un archivo `.envrc` setea las
variables de ambiente que él defina. Una vez que se sale de ese directorio, las
variables se eliminan del ambiente.

Este repositorio provee un archivo .envrc con el siguiente contenido que se usa
exclusivamente durante el proceso de desarrollo:

```bash
export COMPOSE_PROJECT_NAME=App-Control-Academico PATH=$PWD/bin:$PATH APACHE_RUN_USER=$USER APACHE_RUN_GROUP=$(id -ng)
```

Direnv al procesar tal archivo define entonces 4 variables:

* **COMPOSE_PROJECT_NAME:** Cuando usemos docker-compose en el directorio
  `docker/` el proyecto de docker-compose se llamará kimkelen. Si esta variable
  no existiese, entonces se llamaría como el nombre del directorio, que en este
  caso es docker.
* **PATH:** altera el PATH del sistema mientras trabajamos con kimkelen.
  Esencialmente, buscará en el PATH `bin/` del directorio de este repositorio
  antes que en el path del sistema. De esta forma, la distribución podría tener
  instalado php 7, pero dentro del directorio php será 5.3. Asímismo sucede con el
  comando mysql, que accede directamente al mysql de kimkelen dentro del stack de
  docker-compose
* **APACHE_RUN_USER:** usuario con el que correrá el apache dockerizado. Se
  inicializa con su usuario del sistema.
* **APACHE_RUN_GROUP:** lo mismo para el grupo con el que corre el apache
  dockerizado.


### Iniciando el stack de trabajo

Se debe ingresar al directorio `docker/` y correr docker-compose up`:

```
cd docker/ 
docker-compose up
```

El comando anterior inicia por primera vez (o restaura de una corrida previa)
los contenedores que dan soporte al App Control Academico, esto es:

* Apache con el App-Control-Academico instalado
* Mysql
* PHPMyAdmin

**Para comprobar el correcto funcionamiento del stack en desarrollo el siguiente
comando debe devolver:**

```
$ php -v
PHP 5.3.29 (cli) (built: Mar  2 2018 05:47:50) 
Copyright (c) 1997-2014 The PHP Group
Zend Engine v2.3.0, Copyright (c) 1998-2014 Zend Technologies
```
> En caso que no funcione, debe existir algún problema con direnv o el stack no
> ha sido iniciado con docker-compose

## Inicializar con datos

Una vez iniciado el stack completo, se deben correr los siguientes comandos para
inicializar el producto:

```
php symfony App-Control-Academico:flavor demo
```

> Este comando  inicializa la visualización llamada demo. Es la personalización
> de kimkelen usada como punto de partida

```
php symfony propel:build-all-load
```

> Este comando crea la estructura y luego carga la base de datos con datos de
> prueba iniciales

```
php symfony plugin:publish
```

> Este comando actualiza la vista con los propios del flavor aplicado en el
> primer paso. Cada vez que se desee cambiar el flavor, se debe correr este
> comando

```
php symfony project:permissions
```

> Este comando pone los permisos adecuados en el filesystem para trabajar

```
php symfony cache:clear
```

> Elimina datos de cache
