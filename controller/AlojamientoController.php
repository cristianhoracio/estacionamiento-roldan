<?php

require_once __DIR__ . '/../service/AlojamientoService.php';
require_once __DIR__ . '/../repository/AlojamientoRepository.php';
require_once __DIR__ . '/../repository/PrecioRepository.php';
require_once __DIR__ . '/../model/TipoVehiculo.php';
require_once __DIR__ . '/../dto/IngresoDTO.php';

class AlojamientoController{
    private AlojamientoService $service;
    private PrecioRepository $precioRepository;

    public function __construct(){
        $this->service = new AlojamientoService(new AlojamientoRepository());
        $this->precioRepository = new PrecioRepository();
    }

    /**
     * Recibe los datos del formulario ($_POST) y devuelve
     * ['ok' => bool, 'mensaje' => string, 'alojamiento' => ?Alojamiento].
     */
    public function registrarIngreso(array $datos): array{
        $dto = new IngresoDTO(
            (string) ($datos['patente'] ?? ''),
            (string) ($datos['tipo_vehiculo'] ?? '')
        );

        try {
            $alojamiento = $this->service->registrarIngreso($dto);
            return ['ok' => true, 'mensaje' => 'Ingreso registrado correctamente.', 'alojamiento' => $alojamiento];
        } catch (InvalidArgumentException $e) {
            return ['ok' => false, 'mensaje' => $e->getMessage(), 'alojamiento' => null];
        } catch (Exception $e) {
            return ['ok' => false, 'mensaje' => 'Ocurrió un error inesperado. Intentá de nuevo.', 'alojamiento' => null];
        }
    }

    /**
     * Precio vigente por tipo de vehículo, para mostrarlo al seleccionar el tipo.
     * Ej: ['auto' => 1500.0, 'camioneta' => null, 'moto' => 800.0]
     */
    public function preciosVigentes(): array{
        $precios = [];
        foreach (TipoVehiculo::todos() as $tipo) {
            $precios[$tipo] = $this->precioRepository->obtenerVigente($tipo);
        }
        return $precios;
    }
}
