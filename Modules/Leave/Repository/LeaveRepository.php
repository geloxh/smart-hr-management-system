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
}
