<?php
namespace Modules\Employee\Domain;

class Employee {
    public function __construct(
        public ?int $id = null,
        public ?string $employeeId = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
        public ?string $position = null,
        public ?string $department = null,
        public ?string $hireDate = null,
        public string $status = 'active'
    ) {}

    public function toArray(): array {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'position' => $this->position,
            'department' => $this->department,
            'hire_date' => $this->hireDate,
            'status' => $this->status
        ];
    }
}
