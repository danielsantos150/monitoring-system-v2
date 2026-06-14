<?php

header('Content-Type: application/json');

require_once '../../src/Database.php';

$db = Database::getConnection();

$total = $db->query("
    SELECT COUNT(*) total
    FROM servers
")->fetch()['total'];

$active = $db->query("
    SELECT COUNT(*) total
    FROM servers
    WHERE status='active'
")->fetch()['total'];

$maintenance = $db->query("
    SELECT COUNT(*) total
    FROM servers
    WHERE status='maintenance'
")->fetch()['total'];

$inactive = $db->query("
    SELECT COUNT(*) total
    FROM servers
    WHERE status='inactive'
")->fetch()['total'];

$openAlerts = $db->query("
    SELECT COUNT(*) total
    FROM alerts
    WHERE status = 'open'
")->fetch()['total'];

$criticalAlerts = $db->query("
    SELECT COUNT(*) total
    FROM alerts a
    INNER JOIN alert_rules ar
        ON ar.id = a.alert_rule_id
    WHERE
        a.status <> 'resolved'
        AND ar.severity = 'critical'
")->fetch()['total'];

echo json_encode([
    'totalServers' => $total,
    'activeServers' => $active,
    'maintenanceServers' => $maintenance,
    'inactiveServers' => $inactive,
    'openAlerts' => $openAlerts,
    'criticalAlerts' => $criticalAlerts
]);
