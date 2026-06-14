<?php

header('Content-Type: application/json');

require_once '../../src/ServerRepository.php';

try {
    $data = json_decode(
        file_get_contents("php://input"),
        true
    );

    $repository = new ServerRepository();

    $result = $repository->delete(
        (int)$data['id']
    );

    echo json_encode([
        'success' => $result
    ]);
} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
