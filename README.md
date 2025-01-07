
# Pagina Terciario Urquiza

Creación de pagina web para el terciario Urquiza.


## Deploy

Para deployar el proyecto necesitamos tener instalado [Composer](https://getcomposer.org/) , [NodeJS](https://nodejs.org/en/) y [GIT](https://git-scm.com/).

- Usamos Git Bash para descargar el proyecto con git clone en alguna carpeta.
```bash
  git clone https://github.com/LucianoStradiot/Proyecto-Escuela-Urquiza.git
```
- Abrimos el proyecto con Visual Studio Code.
- En la carpeta Back abrimos la terminal y ejecutamos el siguiente codigo para descargar las dependencias:
```bash
  composer i
```
- En la carpeta Front abrimos la terminal y ejecutamos el siguiente codigo para descargar las dependencias:
```bash
  npm install
```
## Environment Variables

Necesitamos tener en la carpeta Front y Back el archivo de las variables de entorno ".env", el cual tiene la variables donde indicamos la conexion a la DB y demas.

