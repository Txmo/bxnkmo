<?php
echo json_encode([
    'data' => [
        'get' => $_GET,
        'post' => $_POST
    ],
    'message' => 'das ist die message',
    'status' => false
]);