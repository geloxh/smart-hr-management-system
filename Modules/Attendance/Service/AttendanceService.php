<?php
namespace Modules\Attendance\Service;

use Modules\Attendance\Repository\AttendanceRepository;

class AttendanceService {
    public function __construct(
        private AttendanceRepository $repository = new AttendanceRepository()
    ) {}

    public function checkIn(int $employeeId): array {
        $today = date('Y-m-d');
        $existing = $this->repository->findByEmployeeAndDate($employeeId, $today);
        
        if ($existing) {
            throw new \Exception('Already checked in today');
        }

        $data = [
            'employee_id' => $employeeId,
            'date' => $today,
            'check_in' => date('H:i:s'),
            'status' => 'present'
        ];

        $id = $this->repository->create($data);
        return ['id' => $id, 'check_in_time' => $data['check_in']];
    }

    public function checkOut(int $employeeId): bool {
        $today = date('Y-m-d');
        $attendance = $this->repository->findByEmployeeAndDate($employeeId, $today);
        
        if (!$attendance) {
            throw new \Exception('No check-in record found for today');
        }

        return $this->repository->update($attendance['id'], ['check_out' => date('H:i:s')]);
    }

    public function getAttendanceReport(string $startDate, string $endDate): array {
        return $this->repository->getAttendanceReport($startDate, $endDate);
    }
}
