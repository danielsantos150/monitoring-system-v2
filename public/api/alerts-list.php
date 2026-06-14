<?php

header('Content-Type: application/json');

require_once '../../src/AlertRepository.php';

$repository = new AlertRepository();

echo json_encode([
    'success' => true,
    'data' => $repository->getAll()
]);
