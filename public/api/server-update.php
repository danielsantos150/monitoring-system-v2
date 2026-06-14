<?php

header('Content-Type: application/json');

require_once '../../src/ServerRepository.php';

try {
    $data = json_decode(
        file_get_contents("php://input"),
        true
    );

    $id = (int)$data['id'];

    unset($data['id']);

    $repository = new ServerRepository();

    $result = $repository->update($id, $data);

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
