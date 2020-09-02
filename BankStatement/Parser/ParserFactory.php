<?php

namespace BankStatement\Parser;
require_once dirname(__DIR__).'/Parser/ParserException.php';
require_once dirname(__DIR__).'/Parser/SparkasseCSVParser.php';

class ParserFactory
{

    private const CSV_PARSERS = [
        SparkasseCSVParser::class
    ];

    private function __construct()
    {
    }

    /**
     * @param $fileName
     * @return StatementParser
     * @throws ParserException
     */
    public static function findParser($fileName): StatementParser
    {
        $mimeType = mime_content_type($fileName);
        switch ($mimeType) {
            case 'text/csv':
            case 'text/plain':
                return self::findCSVParser($fileName);
            default:
                break;
        }
        throw new ParserException();
    }

    /**
     * @param string $fileName
     * @return StatementParser
     * @throws ParserException
     */
    private static function findCSVParser(string $fileName): StatementParser
    {
        foreach (self::CSV_PARSERS as $class) {
            /** @var StatementParser $parser */
            $parser = new $class($fileName);
            if ($parser->isValidFileForParser()) {
                return $parser;
            }
        }
        throw new ParserException();
    }
}