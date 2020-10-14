<?php


namespace Filter;

use PDOStatement;

/**
 * Interface Condition
 * @package Filter
 */
interface Condition
{

    /**
     * @return string
     */
    public function getQueryString(): string;

    /**
     * Binded die Condition an ein PDOStatement
     * {@see PDOStatement::bindValue()}
     *
     * @param PDOStatement $PDOStatement
     */
    public function bindToStatement(PDOStatement $PDOStatement): void;

}