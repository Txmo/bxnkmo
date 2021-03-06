<?php


namespace Filter;

require_once BXNMKO . '/Database/DB.php';
require_once BXNMKO . '/Filter/Filter.php';
require_once BXNMKO . '/Filter/Condition.php';

use Database\DB;
use PDO;
use PDOStatement;


class Group implements Condition
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
     * @var Filter[]
     */
    public $filter;

    public $logicalOperatorId = LogicalOperator::OPERATOR_AND;

    /**
     * @return Group[]
     */
    public static function all(): array
    {
        $query = <<<SQL
        SELECT id, name
        FROM `group`
SQL;
        $stm = DB::connect()->prepare($query);
        if (DB::execute($stm)) {
            return $stm->fetchAll(PDO::FETCH_CLASS, self::class);
        }
        return [];
    }

    /**
     * @param int $groupId
     * @return bool
     */
    public static function deleteForId(int $groupId): bool
    {
        $query = <<<SQL
        DELETE FROM `group` WHERE id = :id
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':id', $groupId, PDO::PARAM_INT);
        if (DB::execute($stm)) {
            return (bool)$stm->rowCount();
        }
        return false;
    }

    public function getQueryString(): string
    {
        $groupConditions = [];
        foreach ($this->filter as $filter) {
            $groupConditions[] = '(' . $filter->getQueryString() . ')';
        }
        if ($groupConditions) {
            return implode(' ' . LogicalOperator::SIGNS[$this->logicalOperatorId] . ' ', $groupConditions);
        }
        return '';
    }

    /**
     * @inheritDoc
     */
    public function bindToStatement(PDOStatement $PDOStatement): void
    {
        foreach ($this->filter as $filter) {
            $filter->bindToStatement($PDOStatement);
        }
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $query = <<<SQL
        INSERT INTO `group` (name) VALUES (:name)
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':name', $this->name);
        if (DB::execute($stm)) {
            $this->id = DB::connect()->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return !empty($this->name) && strlen($this->name) <= 50;
    }

}