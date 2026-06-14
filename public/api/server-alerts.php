<?php

header('Content-Type: application/json');

require_once '../../src/Database.php';

$serverId = (int) $_GET['server_id'];

$db = Database::getConnection();

$stmt = $db->prepare("
    SELECT
        a.*,
        ar.severity
    FROM alerts a

    INNER JOIN alert_rules ar
        ON ar.id = a.alert_rule_id

    WHERE a.server_id = ?

    ORDER BY triggered_at DESC

    LIMIT 20
");

$stmt->execute([$serverId]);

echo json_encode([
    'success' => true,
    'data' => $stmt->fetchAll()
]);
