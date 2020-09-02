<?php


namespace BankStatement\Parser;


abstract class AbstractFileParser
{
    /**
     * @var string $fileName
     */
    public $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

}