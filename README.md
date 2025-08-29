# Symfony + MongoDB CRUD

Proyecto de **CRUD con Symfony** usando **MongoDB** como base de datos.  
Incluye configuración con **Docker** para levantar el entorno rápidamente.

---

## Características
- CRUD completo de productos 🛒
- Integración con **Doctrine ODM** y **MongoDB**
- Configuración lista con **Docker** + **PHP 8.2** + **Apache**
- Soporte para **Symfony Console** y **Web Profiler**

---

## Tecnologías
- [Symfony 6](https://symfony.com/)
- [MongoDB](https://www.mongodb.com/)
- [Docker](https://www.docker.com/)
- PHP 8.2

---

## Instalación y ejecución

```bash
# Clonar el repo
git clone https://github.com/tu-usuario/tu-repo.git
cd tu-repo

# Levantar Docker
docker compose up -d

# Instalar dependencias
docker exec -it tu-contenedor-php composer install

# Ejecutar servidor Symfony (si aplica)
docker exec -it tu-contenedor-php symfony serve -d
