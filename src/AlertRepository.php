<?php

require_once __DIR__ . '/Database.php';

class AlertRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT
                a.*,
                s.hostname,
                ar.severity
            FROM alerts a
            INNER JOIN servers s
                ON s.id = a.server_id
            INNER JOIN alert_rules ar
                ON ar.id = a.alert_rule_id
            ORDER BY a.triggered_at DESC
        ");

        return $stmt->fetchAll();
    }

    public function acknowledge(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE alerts
            SET status = 'acknowledged'
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    public function resolve(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE alerts
            SET
                status = 'resolved',
                resolved_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}
