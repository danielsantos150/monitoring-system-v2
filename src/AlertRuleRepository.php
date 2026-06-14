<?php

require_once __DIR__ . '/Database.php';

class AlertRuleRepository
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
                ar.*,
                mt.name AS metric_name,
                s.hostname
            FROM alert_rules ar
            INNER JOIN metric_types mt
                ON mt.id = ar.metric_type_id
            LEFT JOIN servers s
                ON s.id = ar.server_id
            ORDER BY ar.id DESC
        ");

        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO alert_rules
            (
                server_id,
                metric_type_id,
                operator,
                threshold_value,
                severity,
                is_active
            )
            VALUES
            (
                :server_id,
                :metric_type_id,
                :operator,
                :threshold_value,
                :severity,
                :is_active
            )
        ");

        return $stmt->execute([
            'server_id' => $data['server_id'] ?: null,
            'metric_type_id' => $data['metric_type_id'],
            'operator' => $data['operator'],
            'threshold_value' => $data['threshold_value'],
            'severity' => $data['severity'],
            'is_active' => $data['is_active']
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM alert_rules
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}
