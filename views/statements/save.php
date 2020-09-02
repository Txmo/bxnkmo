<?php

require_once dirname(__DIR__) . '../BankStatement/Parser/ParserFactory.php';
require_once dirname(__DIR__) . '../BankStatement/StatementHandler.php';

use BankStatement\Parser\ParserException;
use BankStatement\Parser\ParserFactory;
use BankStatement\StatementHandler;

$file = dirname(__DIR__) . '../bxnkmo/statements/csv/20200822-75447649-umsatz.CSV';
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