<?php

header('Content-Type: application/json');

require_once '../../src/ServerRepository.php';

try {

    $data = json_decode(
        file_get_contents('php://input'),
        true
    );

    $repository =
        new ServerRepository();

    $repository->create($data);

    echo json_encode([
        'success' => true
    ]);
} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
