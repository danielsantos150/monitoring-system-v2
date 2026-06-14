<?php

header('Content-Type: application/json');

require_once '../../src/Database.php';

$db = Database::getConnection();

$stmt = $db->query("
    SELECT id, hostname
    FROM servers
    ORDER BY hostname
");

echo json_encode([
    'success' => true,
    'data' => $stmt->fetchAll()
]);
