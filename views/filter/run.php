<?php
require_once BXNMKO . '/Filter/Filter.php';
require_once BXNMKO . '/Filter/Field.php';

use Filter\Filter;
use Filter\Field;

$filter = new Filter();

$recipientOperator = (int)$_POST['recipientOperator'] ?? 0;
$recipient = $_POST['recipient'] ?? null;
$filter->addField(Field::RECIPIENT, $recipientOperator, $recipient);

$recipientIbanOperator = (int)$_POST['recipientIbanOperator'] ?? 0;
$recipientIban = $_POST['recipientIban'] ?? null;
$filter->addField(Field::RECIPIENT_IBAN, $recipientIbanOperator, $recipientIban);

$bookingDateOperator = (int)$_POST['bookingDateOperator'] ?? 0;
$bookingDates = $_POST['bookingDate'] ?? null;
$filter->addField(Field::BOOKING_DATE, $bookingDateOperator, $bookingDates);

$usageOperator = (int)$_POST['usageOperator'] ?? 0;
$usage = $_POST['usage'] ?? null;
$filter->addField(Field::USAGE, $usageOperator, $usage);

$amountOperator = (int)$_POST['amountOperator'] ?? 0;
$amounts = $_POST['amount'] ?? null;
$filter->addField(Field::AMOUNT, $amountOperator, $amounts);

if ($stm = $filter->run()) {
    echo json_encode($stm->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

echo json_encode(['failed']);