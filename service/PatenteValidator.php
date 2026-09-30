<?php

require_once __DIR__ . '/../model/TipoVehiculo.php';

class PatenteValidator
{
    // ABC123 (auto, camioneta y moto)
    private const FORMATO_VIEJO = '/^[A-Z]{3}[0-9]{3}$/';

    // A123BCD (solo moto)
    private const FORMATO_NUEVO = '/^[A-Z][0-9]{3}[A-Z]{3}$/';

    // AB123CD (solo auto y camioneta)
    private const FORMATO_MERCOSUR = '/^[A-Z]{2}[0-9]{3}[A-Z]{2}$/';

    // ABC1234 (solo auto y camioneta)
    private const FORMATO_PROVISORIO = '/^[A-Z]{3}[0-9]{4}$/';

    /**
     * Normaliza la patente: mayúsculas, sin espacios ni guiones.
     */
    public static function normalizar(string $patente): string
    {
        $patente = strtoupper(trim($patente));
        return str_replace([' ', '-'], '', $patente);
    }

    /**
     * Formatos permitidos según el tipo de vehículo.
     */
    private static function formatosPara(string $tipoVehiculo): array
    {
        if ($tipoVehiculo === TipoVehiculo::MOTO) {
            return [
                'viejo' => self::FORMATO_VIEJO,
                'nuevo' => self::FORMATO_NUEVO,
            ];
        }
        if ($tipoVehiculo === TipoVehiculo::AUTO || $tipoVehiculo === TipoVehiculo::CAMIONETA) {
            return [
                'viejo'      => self::FORMATO_VIEJO,
                'mercosur'   => self::FORMATO_MERCOSUR,
                'provisorio' => self::FORMATO_PROVISORIO,
            ];
        }
        return [];
    }

    /**
     * Valida la patente según el tipo de vehículo.
     */
    public static function esValida(string $patente, string $tipoVehiculo): bool
    {
        return self::tipoFormato($patente, $tipoVehiculo) !== null;
    }

    /**
     * Devuelve qué formato coincide ('viejo', 'nuevo', 'mercosur', 'provisorio')
     * o null si no coincide con ninguno de los permitidos para ese tipo.
     */
    public static function tipoFormato(string $patente, string $tipoVehiculo): ?string
    {
        $patente = self::normalizar($patente);

        foreach (self::formatosPara($tipoVehiculo) as $nombre => $regex) {
            if (preg_match($regex, $patente) === 1) {
                return $nombre;
            }
        }
        return null;
    }

    /**
     * Ejemplos de formatos válidos, para mostrar en el mensaje de error.
     */
    public static function ejemplos(string $tipoVehiculo): string
    {
        return $tipoVehiculo === TipoVehiculo::MOTO
            ? 'ABC123 o A123BCD'
            : 'ABC123, AB123CD o ABC1234';
    }
}