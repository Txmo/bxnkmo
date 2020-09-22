<?php


namespace Filter;

require_once BXNMKO . '/Filter/ComparisonOperator.php';
require_once BXNMKO . '/Filter/Field.php';
require_once BXNMKO . '/Filter/Filter.php';
require_once BXNMKO . '/Database/DB.php';

use PDOStatement;
use Database\DB;
use Filter\ComparisonOperator;
use Filter\Field;
use Filter\Filter;


abstract class Query
{

    public $query;

    /**
     * @var array e.g. [:recipientUNIQID => exampleRecipient]
     */
    public $parameters = [];

    /**
     * @var array e.g. [recipient = :recipientUNIQID]
     */
    public $conditions = [];

    /**
     * @return string
     */
    abstract public function generateMainQuery(): string;

    /**
     * Query constructor.
     */
    public function __construct()
    {
        $this->query = $this->generateMainQuery();
    }

    public function addCondition(Field $field): void
    {
        if (!in_array($field->comparisonOperator->sign, ComparisonOperator::ALLOWED_SIGNS)) {
            return;
        }
        switch ($field->comparisonOperator->id) {
            case ComparisonOperator::LIKE:
                $placeHolder = ':' . $field->correspondingColumnName . rand(0, 9999);
                $this->conditions[] = '`' . $field->correspondingColumnName . '` ' . ComparisonOperator::ALLOWED_SIGNS[ComparisonOperator::LIKE] . ' ' . $placeHolder;
                $this->parameters[$placeHolder] = '%' . ($field->values[0] ?? '') . '%';
                break;
            case ComparisonOperator::BETWEEN:
                $placeHolderLeft = ':' . $field->correspondingColumnName . rand(0, 9999);
                $placeHolderRight = ':' . $field->correspondingColumnName . rand(0, 9999);
                $this->conditions[] = '`' . $field->correspondingColumnName . '` ' . ComparisonOperator::ALLOWED_SIGNS[ComparisonOperator::BETWEEN] . ' ' . $placeHolderLeft . ' AND ' . $placeHolderRight;
                $this->parameters[$placeHolderLeft] = $field->values[0] ?? 0;
                $this->parameters[$placeHolderRight] = $field->values[1] ?? 0;
                break;
            default:
                $placeHolder = ':' . $field->correspondingColumnName . rand(0, 9999);
                $this->conditions[] = '`' . $field->correspondingColumnName . '` ' . (ComparisonOperator::ALLOWED_SIGNS[$field->comparisonOperator->id] ?? ComparisonOperator::ALLOWED_SIGNS[ComparisonOperator::EQUAL_TO]) . ' ' . $placeHolder;
                $this->parameters[$placeHolder] = $field->values[0] ?? null;
        }
    }

    /**
     * @return PDOStatement|null
     */
    public function run(): ?PDOStatement
    {
        $this->build();
        #echo json_encode($this->query);exit;
        $stm = DB::connect()->prepare($this->query);
        if (DB::execute($stm, $this->parameters)) {
            return $stm;
        }
        return null;
    }

    /**
     * @return void
     */
    abstract public function build(): void;

}