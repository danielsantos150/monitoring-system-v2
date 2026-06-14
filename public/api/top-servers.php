<?php

header('Content-Type: application/json');

require_once '../../src/Database.php';

$db = Database::getConnection();

$stmt = $db->query("
    SELECT
        s.hostname,
        sm.value

    FROM server_metrics sm

    INNER JOIN servers s
        ON s.id = sm.server_id

    WHERE metric_type_id = 1

    ORDER BY sm.collected_at DESC

    LIMIT 10
");

echo json_encode([
    'success' => true,
    'data' => $stmt->fetchAll()
]);
