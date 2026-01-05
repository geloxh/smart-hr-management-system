<?php
namespace Modules\Attendance\Repository;

use Core\Repository\BaseRepository;

class AttendanceRepository extends BaseRepository {
    protected string $table = 'attendance';

    public function findByEmployeeAndDate(int $employeeId, string $date): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE employee_id = ? AND date = ?");
        $stmt->execute([$employeeId, $date]);
        return $stmt->fetch() ?: null;
    }

    public function getAttendanceReport(string $startDate, string $endDate): array {
        $sql = "SELECT a.*, e.first_name, e.last_name, e.employee_id 
                FROM {$this->table} a 
                JOIN employees e ON a.employee_id = e.id 
                WHERE a.date BETWEEN ? AND ? 
                ORDER BY a.date DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }

    public function getEmployeeAttendance(int $employeeId, string $startDate, string $endDate): array {
        $sql = "SELECT * FROM {$this->table} 
                WHERE employee_id = ? AND date BETWEEN ? AND ? 
                ORDER BY date DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$employeeId, $startDate, $endDate]);
        return $stmt->fetchAll();
    }

    public function updateCheckOut(int $employeeId, string $date, string $checkOutTime): bool {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET check_out = ? WHERE employee_id = ? AND date = ?");
        return $stmt->execute([$checkOutTime, $employeeId, $date]);
    }

    public function getTodayAttendance(int $employeeId): ?array {
        return $this->findByEmployeeAndDate($employeeId, date('Y-m-d'));
    }
}
