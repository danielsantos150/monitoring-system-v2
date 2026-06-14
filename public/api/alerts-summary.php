<?php

header('Content-Type: application/json');

require_once '../../src/Database.php';

$db = Database::getConnection();

$stmt = $db->query("
    SELECT
        ar.severity,
        COUNT(*) total

    FROM alerts a

    INNER JOIN alert_rules ar
        ON ar.id = a.alert_rule_id

    GROUP BY ar.severity
");

echo json_encode([
    'success' => true,
    'data' => $stmt->fetchAll()
]);
