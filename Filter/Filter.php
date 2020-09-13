<?php


namespace Filter;
require_once BXNMKO . '/Database/DB.php';
require_once BXNMKO . '/Filter/Field.php';
require_once BXNMKO . '/Filter/FilterQuery.php';

use PDOStatement;
use Database\DB;
use Filter\Field;
use Filter\FilterQuery;


class Filter
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
            return $stm->fetchAll(\PDO::FETCH_CLASS, self::class);
        }
        return [];
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
        if (!$field->comparisonOperator = ComparisonOperator::forId($operatorId)) {
            return;
        }

        $field->setValues($values);
        $this->fields[] = $field;
    }

    /**
     * @return PDOStatement|null
     */
    public function run(): ?PDOStatement
    {
        $query = new FilterQuery();
        $query->addConditionsForFilter($this);
        return $query->run();
    }

}