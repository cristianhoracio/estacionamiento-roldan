<?php

class Database{
    private const HOST = 'localhost';
    private const DB_NAME = 'estacionamiento_roldan';
    private const USER = 'root';
    private const PASSWORD = '';
    private const CHARSET = 'utf8mb4';

    private static ?PDO $conexion = null; //se crea una propiedad de tipo PDO que puede ser nula, para almacenar la conexión a la base de datos

    /**
     * Devuelve siempre la MISMA conexión (patrón Singleton),
     * en vez de crear una nueva cada vez que se llama.
     */
    public static function getConexion(): PDO{
        if (self::$conexion === null) {
            $dsn = "mysql:host=" . self::HOST . ";dbname=" . self::DB_NAME . ";charset=" . self::CHARSET;

            try {
                self::$conexion = new PDO($dsn, self::USER, self::PASSWORD, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die("Error de conexión a la base de datos: " . $e->getMessage());
            }
        }

        return self::$conexion;
    }
}