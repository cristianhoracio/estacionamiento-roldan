<?php

require_once __DIR__ . '/PatenteValidator.php';
require_once __DIR__ . '/../model/TipoVehiculo.php';
require_once __DIR__ . '/../model/Alojamiento.php';
require_once __DIR__ . '/../dto/IngresoDTO.php';
require_once __DIR__ . '/../repository/AlojamientoRepository.php';

class AlojamientoService{
    private AlojamientoRepository $alojamientoRepository;

    public function __construct(AlojamientoRepository $alojamientoRepository)
    {
        $this->alojamientoRepository = $alojamientoRepository;
    }

    /**
     * US-1: registra el ingreso de un vehículo.
     * Lanza InvalidArgumentException si algún dato no cumple las reglas.
     */
    public function registrarIngreso(IngresoDTO $dto): Alojamiento{
        $patente = PatenteValidator::normalizar($dto->getPatente());

        if ($patente === '') {
            throw new InvalidArgumentException('Ingresá la patente del vehículo.');
        }
        if (!PatenteValidator::esValida($patente)) {
            throw new InvalidArgumentException('La patente no tiene un formato válido (ej: ABC123, AB123CD o provisoria).');
        }
        if (!TipoVehiculo::esValido($dto->getTipoVehiculo())) {
            throw new InvalidArgumentException('Seleccioná el tipo de vehículo.');
        }
        if ($this->alojamientoRepository->existePatenteActiva($patente)) {
            throw new InvalidArgumentException("El vehículo {$patente} ya tiene un ingreso activo.");
        }

        // La hora de ingreso se registra sola, no la carga el encargado.
        $horaIngreso = new DateTime('now', new DateTimeZone('America/Argentina/Buenos_Aires'));

        $alojamiento = new Alojamiento($patente, $dto->getTipoVehiculo(), $horaIngreso);
        $alojamiento->setId($this->alojamientoRepository->insertar($alojamiento));

        return $alojamiento;
    }
}
