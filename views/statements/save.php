<?php

require_once BXNMKO . '/BankStatement/Parser/ParserFactory.php';
require_once BXNMKO . '/BankStatement/StatementHandler.php';

use BankStatement\Parser\ParserException;
use BankStatement\Parser\ParserFactory;
use BankStatement\StatementHandler;

$file = BXNMKO . '/statements/csv/20200822-75447649-umsatz.CSV';
echo "<pre>";
$statements = null;
try {
    $parser = ParserFactory::findParser($file);
    $statements = $parser->parse();
} catch (ParserException $exception) {
    echo 'ParserException';
}

if ($statements) {
    $statementHandler = new StatementHandler();
    $statementHandler->statements = $statements;
    echo $statementHandler->insert();
}