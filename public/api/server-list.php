<?php

header('Content-Type: application/json');

require_once '../../src/ServerRepository.php';

try {

    $repository = new ServerRepository();

    echo json_encode([
        'success' => true,
        'data' => $repository->getAll()
    ]);
} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
