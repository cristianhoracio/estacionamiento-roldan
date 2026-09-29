<?php

require_once __DIR__ . '/../config/Database.php';

/**
 * Solo lectura del precio vigente (lo necesita US-1).
 * La carga y modificación de precios es la US-5.
 */
class PrecioRepository
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Database::getConexion();
    }

    /**
     * Precio por hora vigente hoy para un tipo de vehículo,
     * o null si todavía no hay ninguno cargado.
     */
    public function obtenerVigente(string $tipoVehiculo): ?float
    {
        $sql = "SELECT valor_hora FROM precios
                WHERE tipo_vehiculo = :tipo AND fecha_vigencia <= CURDATE()
                ORDER BY fecha_vigencia DESC, id DESC
                LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':tipo' => $tipoVehiculo]);
        $valor = $stmt->fetchColumn();

        return $valor === false ? null : (float) $valor;
    }
}
