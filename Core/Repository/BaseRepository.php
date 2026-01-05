<?php
namespace Core\Repository;

use Core\Database\Connection;

abstract class BaseRepository {
    protected \PDO $pdo;
    protected string $table;

    public function __construct() {
        $this->pdo = Connection::get();
    }

    public function find(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }
}
