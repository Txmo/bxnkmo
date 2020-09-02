<?php


namespace Filter;
require_once dirname(__DIR__) . '/Filter/FilterValues.php';
require_once dirname(__DIR__) . '/Database/DB.php';

use Database\DB;
use Filter\FilterValues;
use Filter\Field;


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
     * @var FilterValues[][]
     */
    public $filterValues = [
        Field::RECIPIENT => [],
        Field::RECIPIENT_IBAN => [],
        Field::BOOKING_DATE => [],
        Field::USAGE => [],
        Field::AMOUNT => []
    ];

    /**
     * @param int $filterId
     * @return Filter
     */
    public static function forId(int $filterId): Filter
    {
        $query = <<<SQL
        SELECT id, name
        FROM filter
        WHERE id = :id
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':id', $filterId);
        if (DB::execute($stm)) {
            $filter = $stm->fetchObject(self::class) ?: new self();
            if ($filter->id) {
                $filterValues = FilterValues::forFilterId($filter->id);
                foreach ($filterValues as $value) {
                    $filter->filterValues[$value->id][] = $value;
                }
            }
            return $filter;
        }
        return new self();
    }

    /**
     * @param int $groupId
     * @return Filter[]
     */
    public static function forGroup(int $groupId): array
    {
        $query = <<<SQL
        SELECT id, name
        FROM filter
        JOIN group_filter gf on filter.id = gf.filter_id
        WHERE gf.group_id = :id
SQL;
        $stm = DB::connect()->prepare($query);
        $stm->bindValue(':id', $groupId, \PDO::PARAM_INT);
        if (DB::execute($stm)) {
            $return = [];
            /**
             * @var bool|Filter $filter
             */
            while ($filter = $stm->fetchObject(self::class)) {
                $filterValues = FilterValues::forFilterId($filter->id);
                foreach ($filterValues as $value) {
                    $filter->filterValues[$value->id][] = $value;
                }
                $return[] = $filter;
            }
            return $return;
        }
        return [];
    }

    /**
     * @return Filter[]
     */
    public static function withoutFilterValues(): array
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

}