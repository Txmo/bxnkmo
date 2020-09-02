<?php


namespace Filter;
require_once dirname(__DIR__) . '/Database/DB.php';

use Database\DB;

class ComparisonOperator
{
    public const ALLOWED_SIGNS = [
        'LIKE',
        '=',
        '!=',
        '<',
        '>',
        'BETWEEN'
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
            return $stm->fetchAll(\PDO::FETCH_CLASS, self::class);
        }
        return [];
    }

    /**
     * @param int $comparisonId
     * @return ComparisonOperator
     */
    public static function getById(int $comparisonId): ComparisonOperator
    {
        $query = <<<SQL
        SELECT id, sign 
        FROM comparison_operator 
        WHERE id = :id 
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':id', $comparisonId, \PDO::PARAM_INT);
        if (DB::execute($stm)) {
            return $stm->fetchObject(self::class) ?: new self();
        }
        return new self();
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
            JOIN field_comparison_operator fco on c_op.id = fco.comparison_operator_id AND fco.field_id = :fieldId
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':fieldId', $fieldId, \PDO::PARAM_INT);
        if (DB::execute($stm)) {
            return $stm->fetchAll(\PDO::FETCH_CLASS, self::class);
        }
        return [];
    }
}