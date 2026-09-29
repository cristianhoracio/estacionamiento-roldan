<?php

class TipoVehiculo
{
    public const AUTO = 'auto';
    public const CAMIONETA = 'camioneta';
    public const MOTO = 'moto';

    /**
     * Devuelve los tipos de vehículo aceptados por el sistema.
     */
    public static function todos(): array
    {
        return [self::AUTO, self::CAMIONETA, self::MOTO];
    }

    public static function esValido(string $tipo): bool
    {
        return in_array($tipo, self::todos(), true);
    }
}
