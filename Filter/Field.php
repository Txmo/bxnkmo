<?php


namespace Filter;

require_once BXNMKO . '/Database/DB.php';
require_once BXNMKO . '/Filter/ComparisonOperator.php';
require_once BXNMKO . '/Filter/Condition.php';
require_once BXNMKO . '/Filter/ExecuteParameter.php';

use Database\DB;
use PDO;
use PDOStatement;


class Field implements Condition
{
    public const RECIPIENT = 1;
    public const RECIPIENT_IBAN = 2;
    public const BOOKING_DATE = 3;
    public const USAGE = 4;
    public const AMOUNT = 5;

    public const DT_INT = 1;
    public const DT_FLOAT = 2;
    public const DT_STRING = 3;
    public const DT_DATE = 4;

    public const PDO_DT_BINDS = [
        self::DT_INT => PDO::PARAM_INT,
        self::DT_FLOAT => PDO::PARAM_STR,
        self::DT_STRING => PDO::PARAM_STR,
        self::DT_DATE => PDO::PARAM_STR
    ];

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
     * ID des Vergleichsoperators
     *
     * @var int
     */
    public $comparisonOperatorId;

    /**
     * @var int $dataType e.g. {@see \PDO::PARAM_INT}
     */
    public $dataType;

    /**
     * @var array
     */
    public $values = [];

    /**
     * @var ExecuteParameter[] $executeParameters
     */
    public $executeParameters = [];

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
            return $stm->fetchAll(PDO::FETCH_CLASS, self::class);
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
        SELECT id, correspondingColumnName FROM field
        WHERE id = :id
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':id', $fieldId, PDO::PARAM_INT);
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

        //all field data with operator id for given filter
        $query = <<<SQL
            SELECT f.id, f.name, f.correspondingColumnName , ffco.comparisonOperatorId as comparisonOperatorId
            FROM field as f 
            JOIN filter_field_comparison_operator ffco on f.id = ffco.fieldId
            WHERE ffco.filterId = :filterId
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':filterId', $filterId, PDO::PARAM_INT);
        if (!DB::execute($stm)) {
            return [];
        }

        $fields = $stm->fetchAll(PDO::FETCH_CLASS, self::class);

        //load values for every field
        foreach ($fields as $field) {
            $field->loadValuesForFilter($filterId);
        }
        return $fields;
    }

    public function getQueryString(): string //TODO SRP?
    {
        switch ($this->comparisonOperatorId) {
            case ComparisonOperator::LIKE:
                $placeholder = ':' . $this->correspondingColumnName . Query::$autoIncrement++;
                $value = '%' . ($this->values[0] ?? '') . '%';
                $this->executeParameters[] = new ExecuteParameter($placeholder, $value);
                return $placeholder . ' ' . ComparisonOperator::ALLOWED_SIGNS[ComparisonOperator::LIKE] . ' ' . $value;
            case ComparisonOperator::BETWEEN:
                $placeholderLeft = ':' . $this->correspondingColumnName . Query::$autoIncrement++;
                $placeholderRight = ':' . $this->correspondingColumnName . Query::$autoIncrement++;
                $this->executeParameters[] = new ExecuteParameter($placeholderLeft, $this->values[0] ?? 0);
                $this->executeParameters[] = new ExecuteParameter($placeholderRight, $this->values[1] ?? 0);
                return '`' . $this->correspondingColumnName . '` ' . ComparisonOperator::ALLOWED_SIGNS[ComparisonOperator::BETWEEN] . ' ' . $placeholderLeft . ' AND ' . $placeholderRight;
            default:
                $placeholder = ':' . $this->correspondingColumnName . Query::$autoIncrement++;
                $this->executeParameters[] = new ExecuteParameter($placeholder, $this->values[0] ?? 0);
                return '`' . $this->correspondingColumnName . '` ' . (ComparisonOperator::ALLOWED_SIGNS[$this->comparisonOperatorId] ?? ComparisonOperator::ALLOWED_SIGNS[ComparisonOperator::EQUAL_TO]) . ' ' . $placeholder;
        }
    }

    /**
     * @inheritDoc
     */
    public function bindToStatement(PDOStatement $PDOStatement): void
    {
        foreach ($this->executeParameters as $parameter) {
            $PDOStatement->bindValue($parameter->placeholder, $parameter->value, self::PDO_DT_BINDS[$this->dataType]);
        }
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
            JOIN filter_field_value ffv on ffco.fieldId = ffv.fieldId AND ffv.filterId = ffco.filterId
            WHERE ffco.filterId = :filterId
            AND ffco.fieldId = :fieldId
            ORDER BY ffv.value
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':filterId', $filterId, PDO::PARAM_INT);
        $stm->bindValue(':fieldId', $this->id, PDO::PARAM_INT);
        if (DB::execute($stm)) {
            $this->values = $stm->fetchAll(PDO::FETCH_COLUMN);
        }
    }

}