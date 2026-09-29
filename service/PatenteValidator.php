<?php

class PatenteValidator
{
    // Formato viejo: 3 letras + 3 números (ej: ABC123)
    private const FORMATO_VIEJO = '/^[A-Z]{3}[0-9]{3}$/';

    // Formato Mercosur: 2 letras + 3 números + 2 letras (ej: AB123CD)
    private const FORMATO_MERCOSUR = '/^[A-Z]{2}[0-9]{3}[A-Z]{2}$/';

    // Provisoria: alfanumérico, sin patrón fijo, entre 6 y 10 caracteres
    private const FORMATO_PROVISORIO = '/^[A-Z0-9]{6,10}$/';

    /**
     * Normaliza la patente: mayúsculas, sin espacios ni guiones.
     */
    public static function normalizar(string $patente): string
    {
        $patente = strtoupper(trim($patente));
        return str_replace([' ', '-'], '', $patente);
    }

    /**
     * Valida si la patente cumple con alguno de los 3 formatos aceptados.
     */
    public static function esValida(string $patente): bool
    {
        $patente = self::normalizar($patente);

        return preg_match(self::FORMATO_VIEJO, $patente) === 1
            || preg_match(self::FORMATO_MERCOSUR, $patente) === 1
            || preg_match(self::FORMATO_PROVISORIO, $patente) === 1;
    }

    /**
     * Devuelve qué tipo de formato coincide (útil para logs o debug).
     * Retorna null si no coincide con ninguno.
     */
    public static function tipoFormato(string $patente): ?string
    {
        $patente = self::normalizar($patente);

        if (preg_match(self::FORMATO_VIEJO, $patente) === 1) {
            return 'viejo';
        }
        if (preg_match(self::FORMATO_MERCOSUR, $patente) === 1) {
            return 'mercosur';
        }
        if (preg_match(self::FORMATO_PROVISORIO, $patente) === 1) {
            return 'provisorio';
        }
        return null;
    }
}