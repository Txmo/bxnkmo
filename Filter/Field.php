<?php


namespace Filter;

require_once dirname(__DIR__) . '/Database/DB.php';

use Database\DB;

class Field
{
    public const RECIPIENT = 1;
    public const RECIPIENT_IBAN = 2;
    public const BOOKING_DATE = 3;
    public const USAGE = 4;
    public const AMOUNT = 5;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $correspondingColumnName;

    /**
     * @return Field[]
     */
    public static function getAll(): array
    {
        $query = <<<SQL
        SELECT * FROM field
SQL;
        $stm = DB::connect()->prepare($query);
        if (DB::execute($stm)) {
            return $stm->fetchAll(\PDO::FETCH_CLASS, self::class);
        }
        return [];
    }

    /**
     * @param int $fieldId
     * @return Field
     */
    public static function getById(int $fieldId): Field
    {
        $query = <<<SQL
        SELECT id, corresponding_column_name FROM field
        WHERE id = :id
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':id', $fieldId, \PDO::PARAM_INT);
        if (DB::execute($stm)) {
            return $stm->fetchObject(self::class) ?: new self();
        }
        return new self();
    }

}