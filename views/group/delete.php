<?php

require_once BXNMKO.'/Filter/Group.php';

use Filter\Group;

$groupId = $_GET['groupId'] ?? null;

echo json_encode([
    'status' => Group::deleteForId($groupId)
]);
