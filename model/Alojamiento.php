<?php

class Alojamiento{
    private ?int $id;
    private string $patente;
    private string $tipoVehiculo;
    private DateTime $horaIngreso;

    public function __construct(string $patente, string $tipoVehiculo, DateTime $horaIngreso, ?int $id = null){
        $this->patente = $patente;
        $this->tipoVehiculo = $tipoVehiculo;
        $this->horaIngreso = $horaIngreso;
        $this->id = $id;
    }

    public function getId(): ?int{
        return $this->id;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function getPatente(): string{
        return $this->patente;
    }

    public function getTipoVehiculo(): string{
        return $this->tipoVehiculo;
    }

    public function getHoraIngreso(): DateTime{
        return $this->horaIngreso;
    }
}
