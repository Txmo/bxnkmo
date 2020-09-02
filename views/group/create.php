<?php

require_once BXNMKO . '/Filter/Group.php';

use Filter\Group;

$group = new Group();
$group->name = $_POST['groupName'] ?? null;
if (!$group->isValid() || !$group->save()) {
    echo json_encode([
        'status' => false,
        'data' => null
    ]);
    exit;
}

echo json_encode([
    'status' => true,
    'data' => [
        'id' => (int)$group->id,
        'name' => e($group->name)
    ]
]);
exit;