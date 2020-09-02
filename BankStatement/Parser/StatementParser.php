<?php

namespace BankStatement\Parser;
require_once dirname(__DIR__).'/BankStatement.php';

use \BankStatement\BankStatement;

interface StatementParser
{

    /**
     * @return BankStatement[]
     */
    public function parse(): array;

    /**
     * @return bool
     */
    public function isValidFileForParser(): bool;

}