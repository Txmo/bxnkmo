<?php


namespace Filter;
require_once BXNMKO . '/Database/DB.php';

use Database\DB;
use PDO;

class ComparisonOperator
{
    public const LESS_THAN = 1;
    public const GREATER_THAN = 2;
    public const EQUAL_TO = 3;
    public const NOT_EQUAL_TO = 4;
    public const LIKE = 5;
    public const BETWEEN = 6;

    public const ALLOWED_SIGNS = [
        self::LESS_THAN => '<',
        self::GREATER_THAN => '>',
        self::EQUAL_TO => '=',
        self::NOT_EQUAL_TO => '!=',
        self::LIKE => 'LIKE',
        self::BETWEEN => 'BETWEEN'
    ];

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $sign;

    /**
     * @return ComparisonOperator[]
     */
    public static function getAll(): array
    {
        $query = <<<SQL
        SELECT * FROM comparison_operator
SQL;
        $stm = DB::connect()->prepare($query);
        if (DB::execute($stm)) {
            return $stm->fetchAll(PDO::FETCH_CLASS, self::class);
        }
        return [];
    }

    /**
     * @param int $comparisonId
     * @return null|ComparisonOperator
     */
    public static function forId(int $comparisonId): ?ComparisonOperator
    {
        if (!$comparisonId) {
            return null;
        }
        $query = <<<SQL
        SELECT id, sign 
        FROM comparison_operator 
        WHERE id = :id 
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':id', $comparisonId, PDO::PARAM_INT);
        if (DB::execute($stm)) {
            return $stm->fetchObject(self::class) ?: null;
        }
        return null;
    }

    /**
     * @param int $fieldId
     * @return ComparisonOperator[]
     */
    public static function forFieldId(int $fieldId): array
    {
        $query = <<<SQL
            SELECT id, sign
            FROM comparison_operator c_op
            JOIN field_comparison_operator fco on c_op.id = fco.comparisonOperatorId AND fco.fieldId = :fieldId
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':fieldId', $fieldId, PDO::PARAM_INT);
        if (DB::execute($stm)) {
            return $stm->fetchAll(PDO::FETCH_CLASS, self::class);
        }
        return [];
    }
}