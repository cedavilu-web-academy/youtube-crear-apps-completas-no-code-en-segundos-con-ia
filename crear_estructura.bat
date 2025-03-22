@echo off
echo Creando estructura del proyecto PetShop...
::mkdir petshop-app
::cd petshop-app

:: Crear carpetas
echo Creando directorios...
mkdir config
mkdir includes
mkdir assets\css
mkdir assets\img
mkdir uploads

:: Crear archivos PHP vacíos
echo Creando archivos PHP...
type nul > create.php
type nul > delete.php
type nul > index.php
type nul > update.php
type nul > functions.php

:: Crear archivos de configuración
echo Creando archivos de configuración...
type nul > config\database.php
type nul > includes\header.php
type nul > includes\footer.php

:: Crear archivo CSS
echo Creando archivo CSS...
type nul > assets\css\styles.css

echo Estructura creada exitosamente en 'petshop-app'!