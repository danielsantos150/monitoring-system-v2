<?php

header('Content-Type: application/json');

require_once '../../src/Database.php';

$db = Database::getConnection();

$stmt = $db->query("
    SELECT
        mt.name,
        AVG(sm.value) average_value

    FROM server_metrics sm

    INNER JOIN metric_types mt
        ON mt.id = sm.metric_type_id

    GROUP BY mt.id
");

echo json_encode([
    'success' => true,
    'data' => $stmt->fetchAll()
]);
