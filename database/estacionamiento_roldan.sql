-- =========================================================
-- Base de datos: estacionamiento_roldan
-- Cubre las 6 US del tablero Trello (US-1 a US-6)
-- =========================================================

CREATE DATABASE IF NOT EXISTS estacionamiento_roldan
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_spanish_ci;

USE estacionamiento_roldan;

-- ---------------------------------------------------------
-- Tabla: cocheras
-- Cubre: US-2 (seleccionar cochera libre)
-- Separadas por pool: autos/camionetas vs. motos
-- ---------------------------------------------------------
CREATE TABLE cocheras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(10) NOT NULL,
    pool ENUM('auto_camioneta', 'moto') NOT NULL,
    estado ENUM('libre', 'ocupada') NOT NULL DEFAULT 'libre'
);

-- ---------------------------------------------------------
-- Tabla: precios
-- Cubre: US-5 (administrar precios por tipo, con vigencia)
-- El precio anterior sigue aplicando hasta la fecha_vigencia
-- del nuevo registro; el servicio (PHP) decide cuál usar.
-- ---------------------------------------------------------
CREATE TABLE precios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_vehiculo ENUM('auto', 'camioneta', 'moto') NOT NULL,
    valor_hora DECIMAL(10,2) NOT NULL,
    fecha_vigencia DATE NOT NULL
);

-- ---------------------------------------------------------
-- Tabla: alojamientos
-- Cubre: US-1 (ingreso), US-3 (egreso), US-4 (pago/ticket),
--        US-6 (listado del día)
-- ---------------------------------------------------------
CREATE TABLE alojamientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patente VARCHAR(10) NOT NULL,
    tipo_vehiculo ENUM('auto', 'camioneta', 'moto') NOT NULL,
    cochera_id INT NULL,
    hora_ingreso DATETIME NOT NULL,
    hora_egreso DATETIME NULL,
    total DECIMAL(10,2) NULL,
    pago_realizado BOOLEAN NOT NULL DEFAULT FALSE,
    estado ENUM('activo', 'cerrado') NOT NULL DEFAULT 'activo',
    CONSTRAINT fk_alojamiento_cochera
        FOREIGN KEY (cochera_id) REFERENCES cocheras(id)
);

-- Índice para acelerar la búsqueda de "patente ya activa"
-- (criterio que charlaron por WhatsApp) y el listado del día (US-6)
CREATE INDEX idx_alojamientos_patente_estado ON alojamientos (patente, estado);
CREATE INDEX idx_alojamientos_hora_ingreso ON alojamientos (hora_ingreso);