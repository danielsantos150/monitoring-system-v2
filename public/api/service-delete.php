<?php

header('Content-Type: application/json');

require_once '../../src/ServiceRepository.php';

$data = json_decode(
    file_get_contents('php://input'),
    true
);

$repository = new ServiceRepository();

echo json_encode([
    'success' => $repository->delete($data['id'])
]);
