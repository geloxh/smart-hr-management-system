<?php
namespace Modules\Leave\Service;

use Modules\Leave\Repository\LeaveRepository;

class LeaveService {
    public function __construct(
        private LeaveRepository $repository = new LeaveRepository()
    ) {}

    public function requestLeave(array $data): int {
        return $this->repository->create($data);
    }

    public function getPendingLeaves(): array {
        return $this->repository->findPending();
    }

    public function approveLeave(int $id): bool {
        return $this->repository->update($id, ['status' => 'approved']);
    }

    public function rejectLeave(int $id): bool {
        return $this->repository->update($id, ['status' => 'rejected']);
    }

    public function getEmployeeLeaves(int $employeeId): array {
        return $this->repository->findByEmployee($employeeId);
    }
}
