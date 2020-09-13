<?php


namespace Filter;
require_once BXNMKO . '/Database/DB.php';
require_once BXNMKO . '/Filter/Query.php';
require_once BXNMKO . '/Filter/Filter.php';

use Database\DB;
use Filter\Filter;

class FilterQuery extends Query
{

    /**
     * @inheritDoc
     */
    public function generateMainQuery(): string
    {
        return <<<SQL
SELECT * FROM bank_statement
SQL;
    }

    /**
     * @inheritDoc
     */
    public function build(): void
    {
        if (empty($this->conditions)) {
            return;
        }
        $this->query .= ' WHERE ' . implode(' AND ', $this->conditions);
    }

    public function addConditionsForFilter(Filter $filter): void
    {
        foreach ($filter->fields as $field) {
            $this->addCondition($field);
        }
    }
}