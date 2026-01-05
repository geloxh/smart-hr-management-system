<?php
namespace Modules\Employee\Repository;

use Core\Repository\BaseRepository;

class EmployeeRepository extends BaseRepository {
    protected string $table = 'employees';

    public function findAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function findByEmployeeId(string $employeeId): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE employee_id = ?");
        $stmt->execute([$employeeId]);
        return $stmt->fetch() ?: null;
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $data['id'] = $id;
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
}