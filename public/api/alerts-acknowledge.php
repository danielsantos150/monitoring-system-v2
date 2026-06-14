<?php

header('Content-Type: application/json');

require_once '../../src/AlertRepository.php';

try {
    $data = json_decode(
        file_get_contents('php://input'),
        true
    );

    $repository = new AlertRepository();

    echo json_encode([
        'success' => $repository->acknowledge(
            (int)$data['id']
        )
    ]);
} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
