<?php

require_once __DIR__ . '/Database.php';

class MetricRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getMetricsByServer(
        int $serverId,
        int $metricTypeId
    ): array {
        $stmt = $this->db->prepare("
            SELECT
                value,
                collected_at
            FROM server_metrics
            WHERE
                server_id = ?
                AND metric_type_id = ?
            ORDER BY collected_at
        ");

        $stmt->execute([
            $serverId,
            $metricTypeId
        ]);

        return $stmt->fetchAll();
    }
}
