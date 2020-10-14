<?php


namespace Filter;


/**
 * Class LogicalOperator
 * @package Filter
 */
class LogicalOperator
{

    public const OPERATOR_AND = 1;
    public const OPERATOR_OR = 2;

    public const SIGNS = [
        self::OPERATOR_AND => 'AND',
        self::OPERATOR_OR => 'OR'
    ];

}