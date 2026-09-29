<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Alojamiento.php';

class AlojamientoRepository{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Database::getConexion();
    }

    /**
     * Indica si ya hay un alojamiento activo (vehículo dentro) con esa patente.
     */
    public function existePatenteActiva(string $patente): bool
    {
        $sql = "SELECT COUNT(*) FROM alojamientos WHERE patente = :patente AND estado = 'activo'";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':patente' => $patente]);

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Guarda el ingreso y devuelve el id generado.
     * cochera_id queda en NULL: la asignación de cochera es la US-2.
     */
    public function insertar(Alojamiento $alojamiento): int
    {
        $sql = "INSERT INTO alojamientos (patente, tipo_vehiculo, hora_ingreso)
                VALUES (:patente, :tipo, :hora_ingreso)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ':patente'      => $alojamiento->getPatente(),
            ':tipo'         => $alojamiento->getTipoVehiculo(),
            ':hora_ingreso' => $alojamiento->getHoraIngreso()->format('Y-m-d H:i:s'),
        ]);

        return (int) $this->conexion->lastInsertId();
    }
}
