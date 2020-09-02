<?php


namespace Filter;

require_once dirname(__DIR__) . '/Database/DB.php';
require_once dirname(__DIR__) . '/Filter/Filter.php';

use Database\DB;
use Filter\Filter;


class Group
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

    /**
     * @param int $groupId
     * @return Group
     */
    public static function forId(int $groupId): Group
    {
        $query = <<<SQL
        SELECT id, 'name'
        FROM `group`
        WHERE id = :id
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':id', $groupId, \PDO::PARAM_INT);
        if (DB::execute($stm)) {
            /**
             * @var bool|Group $group
             */
            $group = $stm->fetchObject(self::class);
            if ($group->id) {
                $group->filter = Filter::forGroup($group->id);
            }
            return $group;
        }
        return new self();
    }

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
            return $stm->fetchAll(\PDO::FETCH_CLASS, self::class);
        }
        return [];
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
        $stm->bindValue(':id', $groupId, \PDO::PARAM_INT);
        if (DB::execute($stm)) {
            return (bool)$stm->rowCount();
        }
        return false;
    }

}