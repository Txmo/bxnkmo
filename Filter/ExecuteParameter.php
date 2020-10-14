<?php


namespace Filter;


/**
 * Class ExecuteParameter
 * @package Filter
 */
class ExecuteParameter
{

    /**
     * Platzhalter welcher für das binden des Parameters an ein PDOStatement benötigt wird
     *
     * @var string $placeholder
     */
    public $placeholder;

    /**
     * Wert welcher mit dem Platzhalter an ein PDOStatement gebinded wird
     *
     * @var int|float|string $value
     */
    public $value;

    /**
     * ExecuteParameter constructor.
     * @param string $placeholder
     * @param $value
     */
    public function __construct(string $placeholder, $value)
    {
        $this->placeholder = $placeholder;
        $this->value = $value;
    }

}