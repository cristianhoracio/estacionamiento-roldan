<?php

/**
 * Datos que llegan del formulario de ingreso.
 */
class IngresoDTO
{
    private string $patente;
    private string $tipoVehiculo;

    public function __construct(string $patente, string $tipoVehiculo){
        $this->patente = $patente;
        $this->tipoVehiculo = $tipoVehiculo;
    }

    public function getPatente(): string
    {
        return $this->patente;
    }

    public function getTipoVehiculo(): string
    {
        return $this->tipoVehiculo;
    }
}
