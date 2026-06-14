<?php

require_once __DIR__ . '/Database.php';

class ServiceRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getByServer(int $serverId): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM services
            WHERE server_id = ?
            ORDER BY name
        ");

        $stmt->execute([$serverId]);

        return $stmt->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO services
            (
                server_id,
                name,
                port,
                status
            )
            VALUES
            (
                :server_id,
                :name,
                :port,
                :status
            )
        ");

        return $stmt->execute([
            'server_id' => $data['server_id'],
            'name' => $data['name'],
            'port' => $data['port'],
            'status' => $data['status']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE services
            SET
                name = :name,
                port = :port,
                status = :status
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'port' => $data['port'],
            'status' => $data['status']
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM services
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}
