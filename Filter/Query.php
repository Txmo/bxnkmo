<?php


namespace Filter;

require_once BXNMKO . '/Filter/ComparisonOperator.php';
require_once BXNMKO . '/Filter/Field.php';
require_once BXNMKO . '/Filter/Filter.php';
require_once BXNMKO . '/Database/DB.php';
require_once BXNMKO . '/Filter/Condition.php';

use PDOStatement;
use Database\DB;


class Query
{
    public static $autoIncrement = 0;

    /**
     * @var string $query
     */
    public $query = 'SELECT * FROM bank_statement';

    /**
     * @var Condition[] $conditions
     */
    public $conditions = [];

    /**
     * @param Condition $condition
     */
    public function addCondition(Condition $condition): void
    {
        $this->conditions[] = $condition;
    }

    public function run(): ?PDOStatement
    {
        $this->build();
        $PDOStatement = DB::connect()->prepare($this->query);
        $this->bind($PDOStatement);

        if (DB::execute($PDOStatement)) {
            return $PDOStatement;
        }
        return null;
    }

    public function build(): void
    {
        $conditions = [];
        foreach ($this->conditions as $condition) {
            $conditions[] = $condition->getQueryString();
        }
        $this->query .= ' WHERE ' . implode(LogicalOperator::SIGNS[LogicalOperator::OPERATOR_AND], $conditions);
    }

    public function bind(PDOStatement $PDOStatement): void
    {
        foreach ($this->conditions as $condition) {
            $condition->bindToStatement($PDOStatement);
        }
    }

}