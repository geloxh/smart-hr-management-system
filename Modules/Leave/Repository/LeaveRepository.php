<?php
namespace Modules\Leave\Repository;

use Core\Repository\BaseRepository;

class LeaveRepository extends BaseRepository {
    protected string $table = 'leaves';

    public function findByEmployee(int $employeeId): array {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE employee_id = ? ORDER BY start_date DESC");
        $stmt->execute([$employeeId]);
        return $stmt->fetchAll();
    }

    public function findPending(): array {
        $stmt = $this->pdo->prepare("SELECT l.*, e.first_name, e.last_name, e.employee_id 
                                   FROM {$this->table} l 
                                   JOIN employees e ON l.employee_id = e.id 
                                   WHERE l.status = 'pending' 
                                   ORDER BY l.start_date");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAll(): array {
        $stmt = $this->pdo->prepare("SELECT l.*, e.first_name, e.last_name, e.employee_id 
                                   FROM {$this->table} l 
                                   JOIN employees e ON l.employee_id = e.id 
                                   ORDER BY l.start_date DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function getLeaveBalance(int $employeeId, string $type, int $year): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as days FROM {$this->table} 
                                   WHERE employee_id = ? AND type = ? AND status = 'approved' 
                                   AND YEAR(start_date) = ?");
        $stmt->execute([$employeeId, $type, $year]);
        $result = $stmt->fetch();
        return $result['days'] ?? 0;
    }
}
