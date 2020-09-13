<?php


namespace Filter;

require_once BXNMKO . '/Database/DB.php';
require_once BXNMKO . '/Filter/ComparisonOperator.php';

use Database\DB;
use Filter\ComparisonOperator;

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
     * @var string
     */
    public $name;

    /**
     * @var ComparisonOperator
     */
    public $comparisonOperator;

    /**
     * @var array
     */
    public $values = [];

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
     * @return null|Field
     */
    public static function forId(int $fieldId): ?Field
    {
        $query = <<<SQL
        SELECT id, corresponding_column_name as correspondingColumnName FROM field
        WHERE id = :id
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':id', $fieldId, \PDO::PARAM_INT);
        if (DB::execute($stm)) {
            return $stm->fetchObject(self::class) ?: null;
        }
        return null;
    }

    /**
     * @param int $filterId
     * @return array
     */
    public static function forFilterId(int $filterId): array
    {
        if (!$filterId) {
            return [];
        }
        /**
         * @var Field[] $fields
         */
        $fields = [];
        //all field data with operator id for given filter
        $query = <<<SQL
            SELECT f.id, f.name, f.corresponding_column_name , ffco.comparison_operator_id
            FROM field as f 
            JOIN filter_field_comparison_operator ffco on f.id = ffco.field_id
            WHERE ffco.filter_id = :filterId
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':filterId', $filterId, \PDO::PARAM_INT);
        if (!DB::execute($stm)) {
            return [];
        }

        while ($row = $stm->fetch(\PDO::FETCH_ASSOC)) {
            $field = new self();
            $field->id = $row['id'];
            $field->correspondingColumnName = $row['corresponding_column_name'];
            $field->name = $row['name'];
            $field->comparisonOperator = ComparisonOperator::forId($row['comparison_operator_id']);
            $fields[] = $field;
        }

        //load values for every field
        foreach ($fields as $field) {
            $field->loadValuesForFilter($filterId);
        }
        return $fields;
    }

    public function setValues($values): void
    {
        $this->values = !is_array($values) ? [$values] : $values;
    }

    /**
     * @param int $filterId
     */
    public function loadValuesForFilter(int $filterId): void
    {
        if (!$this->id || !$filterId) {
            return;
        }

        $query = <<<SQL
            SELECT ffv.value
            FROM filter_field_comparison_operator as ffco 
            JOIN filter_field_value ffv on ffco.field_id = ffv.field_id AND ffv.filter_id = ffco.filter_id
            WHERE ffco.filter_id = :filterId
            AND ffco.field_id = :fieldId
            ORDER BY ffv.value
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':filterId', $filterId, \PDO::PARAM_INT);
        $stm->bindValue(':fieldId', $this->id, \PDO::PARAM_INT);
        if (DB::execute($stm)) {
            $this->values = $stm->fetchAll(\PDO::FETCH_COLUMN);
        }
    }

}