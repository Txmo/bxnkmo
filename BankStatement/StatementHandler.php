<?php


namespace BankStatement;

require_once dirname(__DIR__) . '/BankStatement/BankStatement.php';
require_once dirname(__DIR__) . '/Database/DB.php';

use BankStatement\BankStatement;
use Database\DB;


class StatementHandler
{
    /**
     * @var BankStatement[]
     */
    public $statements = [];

    /**
     * @var int $insertId
     */
    public $insertId;

    /**
     * StatementHandler constructor.
     */
    public function __construct()
    {
    }


    /**
     *
     * @return bool
     */
    public function insert(): bool
    {
        if (empty($this->statements)) {
            return false;
        }

        $this->createNewBankStatementInsert();
        if (!$this->insertId) {
            return false;
        }

        return $this->insertBankStatements();
    }

    /**
     * Erstellt einen Eintrag in der bank_statement_insert Tabelle und schreibt die ID des Eintrags in $insertId
     * @return void
     */
    private function createNewBankStatementInsert(): void
    {
        $query = <<<SQL
            INSERT INTO bank_statement_insert () VALUES ()
SQL;
        $stm = DB::connect()->prepare($query);
        if (DB::execute($stm)) {
            $this->insertId = (int)DB::connect()->lastInsertId();
        }


    }

    /**
     *
     * @return int
     */
    private function insertBankStatements(): int
    {
        $query = <<<SQL
            INSERT INTO bank_statement (order_iban, booking_date, booking_text, `usage`, bank_statement_insert_id, creditor_id, mandate_reference, customer_reference, collective_reference, recipient, recipient_iban, recipient_bic, amount)
            VALUES 
SQL;

        $values = $this->addStatementsToQuery($query);

        $query .= <<<SQL
            ON DUPLICATE KEY UPDATE id = id
SQL;

        $stm = DB::connect()->prepare($query);
        if (DB::execute($stm, $values)) {
            return $stm->rowCount();
        }
        return 0;
    }

    /**
     *
     * @param string $query
     * @return array
     */
    private function addStatementsToQuery(string &$query): array
    {
        $values = [];
        $queryStrings = [];
        foreach ($this->statements as $key => $statement) {
            $queryStrings[] = "(:order_iban{$key}, :booking_date{$key}, :booking_text{$key}, :usage{$key}, :bank_statement_insert_id{$key}, :creditor_id{$key}, :mandate_reference{$key}, :customer_reference{$key}, :collective_reference{$key}, :recipient{$key}, :recipient_iban{$key}, :recipient_bic{$key}, :amount{$key})";
            $values[':order_iban' . $key] = $statement->orderIBAN;
            $values[':booking_date' . $key] = $statement->bookingDate;
            $values[':booking_text' . $key] = $statement->bookingText;
            $values[':usage' . $key] = $statement->usage;
            $values[':bank_statement_insert_id' . $key] = $this->insertId;
            $values[':creditor_id' . $key] = $statement->creditorId;
            $values[':mandate_reference' . $key] = $statement->mandateReference;
            $values[':customer_reference' . $key] = $statement->customerReference;
            $values[':collective_reference' . $key] = $statement->collectiveReference;
            $values[':recipient' . $key] = $statement->recipient;
            $values[':recipient_iban' . $key] = $statement->recipientIBAN;
            $values[':recipient_bic' . $key] = $statement->recipientBIC;
            $values[':amount' . $key] = $statement->amount;
        }

        $query .= implode(',', $queryStrings);

        return $values;
    }

}