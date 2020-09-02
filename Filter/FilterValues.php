<?php


namespace Filter;
require_once dirname(__DIR__) . '/Filter/Field.php';
require_once dirname(__DIR__) . '/Filter/ComparisonOperator.php';

use Database\DB;
use Filter\Field;
use Filter\ComparisonOperator;

class FilterValues
{
    /**
     * @var Field
     */
    public $field;

    /**
     * @var ComparisonOperator
     */
    public $comparisonOperator;

    /**
     * @var int|float|string
     */
    public $value;

    /**
     * @param int $filterId
     * @return Field[]
     */
    public static function forFilterId(int $filterId): array
    {
        $query = <<<SQL
        SELECT field_id, comparison_operator_id, 'value'
        FROM filter_values
        WHERE filter_id = :filterId
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':filterId', $filterId, \PDO::PARAM_INT);
        if (DB::execute($stm)) {
            while ($row = $stm->fetch(\PDO::FETCH_ASSOC)) {
                $filterValues = new self();
                $filterValues->field = Field::getById($row['field_id']);
                $filterValues->comparisonOperator = ComparisonOperator::getById($row['comparison_operator_id']);
                $filterValues->value = $row['value'];
            }
        }
        return [];
    }

}