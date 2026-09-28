# Estacionamiento Roldán

Sistema de gestión de estacionamiento: registro de ingreso/egreso de vehículos, cocheras y precios.

## Equipo
- Cristian Aquino Valdez
- Flavia Lorena Benoit
- Mauro Joel Cruz

## Tecnologías
- PHP orientado a objetos
- MySQL
- XAMPP (Apache + MySQL local)

## Estructura del proyecto
```
/models     → clases de entidad (Vehiculo, Alojamiento, Cochera, Precio)
/services   → lógica de negocio (validaciones, repositorios)
/config     → conexión a la base de datos
/public     → pantallas
```


## Instalación
1. Cloná el repo dentro de `htdocs` de XAMPP.
2. Iniciá Apache y MySQL desde el panel de XAMPP.
3. Importá el script de la base de datos (`/database/estacionamiento_roldan.sql`).
4. Accedé a `http://localhost/estacionamiento-roldan/public/`.
