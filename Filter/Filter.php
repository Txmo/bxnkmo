<?php


namespace Filter;
require_once BXNMKO . '/Database/DB.php';
require_once BXNMKO . '/Filter/Field.php';
require_once BXNMKO . '/Filter/Condition.php';

use PDO;
use Database\DB;
use PDOStatement;


class Filter implements Condition
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var Field[]
     */
    public $fields = [];

    public $logicalOperatorId = LogicalOperator::OPERATOR_AND;

    /**
     * @return Filter[]
     */
    public static function withoutFields(): array
    {
        $query = <<<SQL
            SELECT id, name
            FROM filter
SQL;
        $stm = DB::connect()->prepare($query);
        if (DB::execute($stm)) {
            return $stm->fetchAll(PDO::FETCH_CLASS, self::class);
        }
        return [];
    }

    /**
     * @return string
     */
    public function getQueryString(): string
    {
        $filterConditions = [];
        foreach ($this->fields as $field) {
            $filterConditions[] = $field->getQueryString();
        }
        if ($filterConditions) {
            return implode(' ' . LogicalOperator::SIGNS[$this->logicalOperatorId] . ' ', $filterConditions);
        }
        return '';
    }

    /**
     * @inheritDoc
     */
    public function bindToStatement(PDOStatement $PDOStatement): void
    {
        foreach ($this->fields as $field) {
            $field->bindToStatement($PDOStatement);
        }
    }

    /**
     * @param int $fieldId
     * @param int $operatorId
     * @param $values
     */
    public function addField(int $fieldId, int $operatorId, $values): void
    {
        if (!$fieldId || !$operatorId) {
            return;
        }
        if (!$field = Field::forId($fieldId)) {
            return;
        }
        $field->comparisonOperatorId = $operatorId;

        $field->setValues($values);
        $this->fields[] = $field;
    }

}