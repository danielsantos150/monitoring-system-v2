<?php

header('Content-Type: application/json');

require_once '../../src/AlertRuleRepository.php';

try {
    $data = json_decode(
        file_get_contents('php://input'),
        true
    );

    $repository = new AlertRuleRepository();

    echo json_encode([
        'success' => $repository->delete($data['id'])
    ]);
} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
