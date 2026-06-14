<?php

header('Content-Type: application/json');

require_once '../../src/Database.php';

$db = Database::getConnection();

$stmt = $db->query("
    SELECT *
    FROM metric_types
    ORDER BY name
");

echo json_encode([
    'success' => true,
    'data' => $stmt->fetchAll()
]);
