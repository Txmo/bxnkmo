<?php

require_once BXNMKO . '/Filter/Filter.php';

use Filter\Filter;
use Filter\ComparisonOperator;
use Filter\Field;

$filters = Filter::withoutFields();

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Filters</title>
    <link rel="stylesheet" href="../../css/materialize.min.css">
    <script type="text/javascript" src="../../js/Helper.js"></script>
    <script type="text/javascript" src="../../js/Ajax.js"></script>
    <script type="text/javascript" src="../../js/OperatorHelper.js"></script>
    <script type="text/javascript" src="../../js/Filter.js"></script>
</head>
<body>
<nav class="row">

</nav>
<div id="main" class="row">
    <aside class="col s3 grey">
    </aside>
    <main class="col s9">
        <div id="idExistingFilter">
            <table id="idExistingFilterTable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="idExistingFilterBody">
                <?php foreach ($filters as $filter) { ?>
                    <tr>
                        <td><?= e($filter->name) ?></td>
                        <td>
                            <button type="button" class="btn">Edit</button>
                            <button type="button" class="btn">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div id="idFilterContainer">
            <div class="row">
                <h4>Filter</h4>
            </div>
            <form id="idFilterForm" method="post" action="<?= DOMAIN ?>/views/filter/save.php">
                <div class="row">
                    <div class="col s2">
                        <h5 class="left-align">Recipient</h5>
                    </div>
                    <div class="input-field col s2">
                        <select name="recipientOperator" id="idRecipientOperator">
                            <option value="">Select Operator</option>
                            <?php foreach (ComparisonOperator::forFieldId(Field::RECIPIENT) as $operator) { ?>
                                <option value="<?= (int)$operator->id ?>"><?= e($operator->sign) ?></option>
                            <?php } ?>
                        </select>
                        <label for="idRecipientOperator">Operator</label>
                    </div>
                    <div class="input-field col s8">
                        <label for="idRecipient">Recipient</label>
                        <input id="idRecipient" type="text" name="recipient" maxlength="50">
                    </div>
                </div>
                <div class="row">
                    <div class="col s2">
                        <h5 class="left-align">Recipient IBAN</h5>
                    </div>
                    <div class="input-field col s2">
                        <select name="recipientIbanOperator" id="idRecipientIbanOperator">
                            <option value="">Select Operator</option>
                            <?php foreach (ComparisonOperator::forFieldId(Field::RECIPIENT_IBAN) as $operator) { ?>
                                <option value="<?= (int)$operator->id ?>"><?= e($operator->sign) ?></option>
                            <?php } ?>
                        </select>
                        <label for="idRecipientIbanOperator">Operator</label>
                    </div>
                    <div class="input-field col s8">
                        <label for="idRecipientIban">Recipient IBAN</label>
                        <input id="idRecipientIban" type="text" name="recipientIban" maxlength="32">
                    </div>
                </div>
                <div class="row">
                    <div class="col s2">
                        <h5 class="left-align">Booking Date</h5>
                    </div>
                    <div class="input-field col s2">
                        <select name="bookingDateOperator" id="idBookingDateOperator">
                            <option value="">Select Operator</option>
                            <?php foreach (ComparisonOperator::forFieldId(Field::BOOKING_DATE) as $operator) { ?>
                                <option value="<?= (int)$operator->id ?>"><?= e($operator->sign) ?></option>
                            <?php } ?>
                        </select>
                        <label for="idBookingDateOperator">Operator</label>
                    </div>
                    <div id="idBookingDateContainer" class="col s8">
                        <label for="idBookingDate">&nbsp;</label>
                        <input id="idBookingDate" type="date" name="bookingDate[]">
                    </div>
                    <div id="idBookingDateBetweenAnd" class="col s2 hide">
                        <h5 style="margin-top: 25px;">AND</h5>
                    </div>
                    <div id="idBookingDateBetweenContainer" class="input-field col s3 hide">
                        <label for="idBookingDateBetween">&nbsp;</label>
                        <input id="idBookingDateBetween" type="date" name="bookingDate[]">
                    </div>
                </div>
                <div class="row">
                    <div class="col s2 ">
                        <h5 class="left-align">Usage</h5>
                    </div>
                    <div class="input-field col s2">
                        <select name="usageOperator" id="idUsageOperator">
                            <option value="">Select Operator</option>
                            <?php foreach (ComparisonOperator::forFieldId(Field::USAGE) as $operator) { ?>
                                <option value="<?= (int)$operator->id ?>"><?= e($operator->sign) ?></option>
                            <?php } ?>
                        </select>
                        <label for="idUsageOperator">Operator</label>
                    </div>
                    <div class="input-field col s8">
                        <label for="idUsage">Usage</label>
                        <input id="idUsage" type="text" name="usage" maxlength="500">
                    </div>
                </div>
                <div class="row">
                    <div class="col s2">
                        <h5 class="left-align">Amount</h5>
                    </div>
                    <div class="input-field col s2">
                        <select name="amountOperator" id="idAmountOperator">
                            <option value="">Select Operator</option>
                            <?php foreach (ComparisonOperator::forFieldId(Field::AMOUNT) as $operator) { ?>
                                <option value="<?= (int)$operator->id ?>"><?= e($operator->sign) ?></option>
                            <?php } ?>
                        </select>
                        <label for="idAmountOperator">Operator</label>
                    </div>
                    <div id="idAmountContainer" class="input-field col s8">
                        <label for="idAmount">Amount</label>
                        <input id="idAmount" type="number" name="amount[]" step="0.01">
                    </div>
                    <div id="idAmountBetweenAnd" class="col s2 hide">
                        <h5 style="margin-top: 25px;">AND</h5>
                    </div>
                    <div id="idAmountBetweenContainer" class="input-field col s3 hide">
                        <label for="idAmountBetween">Amount</label>
                        <input id="idAmountBetween" type="number" name="amount[]" step="0.01">
                    </div>
                </div>
            </form>
        </div>
        <div id="idResultsContainer"></div>
    </main>
</div>
<footer class="row"></footer>

<script type="text/javascript" src="../../js/materialize.min.js"></script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        M.FormSelect.init(document.querySelectorAll('select'));

        const dateHelper = new OperatorHelper({
            selectDOM: document.getElementById('idBookingDateOperator'),
            mainDOM: document.getElementById('idBookingDateContainer'),
            andDOM: document.getElementById('idBookingDateBetweenAnd'),
            betweenDOM: document.getElementById('idBookingDateBetweenContainer')
        });

        const amountHelper = new OperatorHelper({
            selectDOM: document.getElementById('idAmountOperator'),
            mainDOM: document.getElementById('idAmountContainer'),
            andDOM: document.getElementById('idAmountBetweenAnd'),
            betweenDOM: document.getElementById('idAmountBetweenContainer')
        });

        const filter = new Filter();
        filter.values.push({
            operator: document.getElementById('idRecipientOperator'),
            value: [document.getElementById('idRecipient')],
            key: filter.values.length
        });
        filter.values.push({
            operator: document.getElementById('idRecipientIbanOperator'),
            value: [document.getElementById('idRecipientIban')],
            key: filter.values.length
        });
        filter.values.push({
            operator: document.getElementById('idBookingDateOperator'),
            value: [
                document.getElementById('idBookingDate'),
                document.getElementById('idBookingDateBetween')
            ],
            key: filter.values.length
        });
        filter.values.push({
            operator: document.getElementById('idUsageOperator'),
            value: [document.getElementById('idUsage')],
            key: filter.values.length
        });
        filter.values.push({
            operator: document.getElementById('idAmountOperator'),
            value: [
                document.getElementById('idAmount'),
                document.getElementById('idAmountBetween')
            ],
            key: filter.values.length
        });
        filter.init();
        console.log(filter);
    });
</script>
</body>
</html>