<?php


namespace Database;

use PDO;
use PDOException;
use PDOStatement;

class DB
{
    private const HOST = '';
    private const USERNAME = '';
    private const PASSWORD = '';
    private const DATABASE = '';

    /**
     * @var PDO $pdoConnection
     */
    private static $pdoConnection;

    /**
     * Erstellt eine PDO Instanz wenn noch keine erstellt wurde und gibt diese zurück
     * @return PDO
     */
    public static function connect(): PDO
    {
        if (!self::$pdoConnection) {
            try {
                $pdo = new PDO('mysql:dbname=' . self::DATABASE . ';host=' . self::HOST . ';port=3306;charset=utf8mb4', self::USERNAME, self::PASSWORD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdoConnection = $pdo;
            } catch (PDOException $exception) {
                die('CONNECTION FAILED');
            }
        }
        return self::$pdoConnection;
    }

    /**
     * Führt das übergebenen Statement aus
     * @param PDOStatement $PDOStatement
     * @param array|null $params
     * @return bool
     */
    public static function execute(PDOStatement $PDOStatement, ?array $params = null): bool
    {
        try{
            return $PDOStatement->execute($params);
        }catch(PDOException $exception){
            error_log($exception->getMessage().' '.$exception->getFile().' '.$exception->getLine());
            return false;
        }
    }
}