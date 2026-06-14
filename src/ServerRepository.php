<?php

require_once __DIR__ . '/Database.php';

class ServerRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT *
            FROM servers
            ORDER BY hostname
        ");

        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM servers
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        $server = $stmt->fetch();

        return $server ?: null;
    }

    private function validate(array $data): void
    {
        if (empty($data['hostname'])) {
            throw new Exception(
                'Hostname is required'
            );
        }

        if (empty($data['ip_address'])) {
            throw new Exception(
                'IP Address is required'
            );
        }

        if (
            !filter_var(
                $data['ip_address'],
                FILTER_VALIDATE_IP
            )
        ) {
            throw new Exception(
                'Invalid IP Address'
            );
        }
    }

    public function create(array $data): bool
    {
        $this->validate($data);

        $stmt = $this->db->prepare("
            INSERT INTO servers
            (
                hostname,
                ip_address,
                description,
                location,
                os_name,
                os_version,
                cpu_cores,
                total_memory_mb,
                total_disk_gb,
                environment,
                status
            )
            VALUES
            (
                :hostname,
                :ip_address,
                :description,
                :location,
                :os_name,
                :os_version,
                :cpu_cores,
                :memory,
                :disk,
                :environment,
                :status
            )
        ");

        return $stmt->execute([
            'hostname' => $data['hostname'],
            'ip_address' => $data['ip_address'],
            'description' => $data['description'] ?? null,
            'location' => $data['location'] ?? null,
            'os_name' => $data['os_name'] ?? null,
            'os_version' => $data['os_version'] ?? null,
            'cpu_cores' => $data['cpu_cores'] ?? null,
            'memory' => $data['total_memory_mb'] ?? null,
            'disk' => $data['total_disk_gb'] ?? null,
            'environment' => $data['environment'],
            'status' => $data['status']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $this->validate($data);

        $stmt = $this->db->prepare("
            UPDATE servers
            SET
                hostname = :hostname,
                ip_address = :ip_address,
                description = :description,
                location = :location,
                os_name = :os_name,
                os_version = :os_version,
                cpu_cores = :cpu_cores,
                total_memory_mb = :memory,
                total_disk_gb = :disk,
                environment = :environment,
                status = :status
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'hostname' => $data['hostname'],
            'ip_address' => $data['ip_address'],
            'description' => $data['description'],
            'location' => $data['location'],
            'os_name' => $data['os_name'],
            'os_version' => $data['os_version'],
            'cpu_cores' => $data['cpu_cores'],
            'memory' => $data['total_memory_mb'],
            'disk' => $data['total_disk_gb'],
            'environment' => $data['environment'],
            'status' => $data['status']
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM servers
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}
