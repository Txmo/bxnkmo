<?php


namespace BankStatement\Parser;
require_once BXNMKO . 'BankStatement/Parser/StatementParser.php';
require_once BXNMKO . 'BankStatement/Parser/AbstractFileParser.php';
require_once BXNMKO . 'BankStatement/BankStatement.php';

use BankStatement\BankStatement;
use DateTime;

class SparkasseCSVParser extends AbstractFileParser implements StatementParser
{
    public const ORDER_IBAN = 0;
    public const BOOKING_DATE = 1;
    public const BOOKING_TEXT = 3;
    public const USAGE = 4;
    public const CREDITOR_ID = 5;
    public const MANDATE_REFERENCE = 6;
    public const CUSTOMER_REFERENCE = 7;
    public const COLLECTIVE_REFERENCE = 8;
    public const RECIPIENT = 11;
    public const RECIPIENT_IBAN = 12;
    public const RECIPIENT_BIC = 13;
    public const AMOUNT = 14;

    public const CSV_HEADER = [
        0 => 'Auftragskonto',
        1 => 'Buchungstag',
        2 => 'Valutadatum',
        3 => 'Buchungstext',
        4 => 'Verwendungszweck',
        5 => 'Glaeubiger ID',
        6 => 'Mandatsreferenz',
        7 => 'Kundenreferenz (End-to-End)',
        8 => 'Sammlerreferenz',
        9 => 'Lastschrift Ursprungsbetrag',
        10 => 'Auslagenersatz Ruecklastschrift',
        11 => 'Beguenstigter/Zahlungspflichtiger',
        12 => 'Kontonummer/IBAN',
        13 => 'BIC (SWIFT-Code)',
        14 => 'Betrag',
        15 => 'Waehrung',
        16 => 'Info'
    ];

    public const DELIMITER = ';';


    /**
     * @inheritDoc
     */
    public function parse(): array
    {
        $handle = fopen($this->fileName, 'r');
        fgetcsv($handle, 0, self::DELIMITER); //remove header row
        $statements = [];
        while ($row = fgetcsv($handle, 0, self::DELIMITER)) {
            $statements[] = $this->createStatementFromRow($row);
        }
        return $statements;
    }

    /**
     * @param array $row
     * @return BankStatement
     */
    private function createStatementFromRow(array $row): BankStatement
    {
        $statement = new BankStatement();
        $statement->orderIBAN = trim($row[self::ORDER_IBAN]);
        $date = DateTime::createFromFormat('d.m.y', trim($row[self::BOOKING_DATE]));
        $statement->bookingDate = $date->format('Y-m-d');
        $statement->bookingText = utf8_encode(trim(preg_replace('/\s+/', ' ', $row[self::BOOKING_TEXT])));
        $statement->usage = utf8_encode(trim(preg_replace('/\s+/', ' ', $row[self::USAGE])));
        $statement->creditorId = utf8_encode(trim(preg_replace('/\s+/', ' ', $row[self::CREDITOR_ID])));
        $statement->mandateReference = utf8_encode(trim(preg_replace('/\s+/', ' ', $row[self::MANDATE_REFERENCE])));
        $statement->customerReference = utf8_encode(trim(preg_replace('/\s+/', ' ', $row[self::CUSTOMER_REFERENCE])));
        $statement->collectiveReference = utf8_encode(trim(preg_replace('/\s+/', ' ', $row[self::COLLECTIVE_REFERENCE])));
        $statement->recipient = utf8_encode(trim(preg_replace('/\s+/', ' ', $row[self::RECIPIENT])));
        $statement->recipientIBAN = utf8_encode(trim(preg_replace('/\s+/', ' ', $row[self::RECIPIENT_IBAN])));
        $statement->recipientBIC = utf8_encode(trim(preg_replace('/\s+/', ' ', $row[self::RECIPIENT_BIC])));
        $statement->amount = (float)str_replace(',', '.', str_replace('.', '', $row[self::AMOUNT]));
        return $statement;
    }

    /**
     * @return bool
     */
    public function isValidFileForParser(): bool
    {
        $handle = fopen($this->fileName, 'r');
        $header = fgetcsv($handle, 0, self::DELIMITER);
        fclose($handle);
        return $header === self::CSV_HEADER;
    }
}