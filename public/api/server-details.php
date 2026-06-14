<?php

header('Content-Type: application/json');

require_once '../../src/ServerRepository.php';

$id = (int) $_GET['id'];

$repository = new ServerRepository();

echo json_encode([
    'success' => true,
    'data' => $repository->getById($id)
]);
