<?php
namespace Modules\Employee\Service;

use Modules\Employee\Repository\EmployeeRepository;
use Modules\Employee\Domain\Employee;

class EmployeeService {
    public function __construct(
        private EmployeeRepository $repository = new EmployeeRepository()
    ) {}

    public function getAllEmployees(): array {
        return $this->repository->findAll();
    }

    public function createEmployee(array $data): int {
        $data['employee_id'] = $this->generateEmployeeId();
        return $this->repository->create($data);
    }

    public function getEmployee(int $id): ?array {
        return $this->repository->find($id);
    }

    public function updateEmployee(int $id, array $data): bool {
        return $this->repository->update($id, $data);
    }

    private function generateEmployeeId(): string {
        return 'EMP' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
}
