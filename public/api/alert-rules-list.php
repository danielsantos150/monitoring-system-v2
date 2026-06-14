<?php

header('Content-Type: application/json');

require_once '../../src/AlertRuleRepository.php';

$repository = new AlertRuleRepository();

echo json_encode([
    'success' => true,
    'data' => $repository->getAll()
]);
