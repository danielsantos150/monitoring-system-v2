<?php

header('Content-Type: application/json');

require_once '../../src/ServiceRepository.php';

$serverId = (int)$_GET['server_id'];

$repository = new ServiceRepository();

echo json_encode([
    'success' => true,
    'data' => $repository->getByServer($serverId)
]);
