<?php
namespace Modules\Leave\Service;

use Modules\Leave\Repository\LeaveRepository;

class LeaveService {
    public function __construct(
        private LeaveRepository $repository = new LeaveRepository()
    ) {}

    public function requestLeave(array $data): int {
        // Validate dates
        if (strtotime($data['start_date']) > strtotime($data['end_date'])) {
            throw new \Exception('Start date cannot be after end date');
        }

        // Check for overlapping leaves
        if ($this->hasOverlappingLeave($data['employee_id'], $data['start_date'], $data['end_date'])) {
            throw new \Exception('You already have a leave request for this period');
        }

        return $this->repository->create($data);
    }

    public function getPendingLeaves(): array {
        return $this->repository->findPending();
    }

    public function getAllLeaves(): array {
        return $this->repository->findAll();
    }

    public function approveLeave(int $id): bool {
        return $this->repository->updateStatus($id, 'approved');
    }

    public function rejectLeave(int $id): bool {
        return $this->repository->updateStatus($id, 'rejected');
    }

    public function getEmployeeLeaves(int $employeeId): array {
        return $this->repository->findByEmployee($employeeId);
    }

    public function getLeaveBalance(int $employeeId): array {
        $currentYear = date('Y');
        
        return [
            'sick' => 10 - $this->repository->getLeaveBalance($employeeId, 'sick', $currentYear),
            'vacation' => 20 - $this->repository->getLeaveBalance($employeeId, 'vacation', $currentYear),
            'personal' => 5 - $this->repository->getLeaveBalance($employeeId, 'personal', $currentYear)
        ];
    }

    private function hasOverlappingLeave(int $employeeId, string $startDate, string $endDate): bool {
        $leaves = $this->repository->findByEmployee($employeeId);
        
        foreach ($leaves as $leave) {
            if ($leave['status'] === 'rejected') continue;
            
            if (($startDate <= $leave['end_date']) && ($endDate >= $leave['start_date'])) {
                return true;
            }
        }
        
        return false;
    }
}
