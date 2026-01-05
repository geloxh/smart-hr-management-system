<?php
namespace Modules\Attendance\Domain;

class Attendance {
    public function __construct(
        public ?int $id = null,
        public ?int $employeeId = null,
        public ?string $date = null,
        public ?string $checkIn = null,
        public ?string $checkOut = null,
        public string $status = 'present'
    ) {}

    public function toArray(): array {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'date' => $this->date,
            'check_in' => $this->checkIn,
            'check_out' => $this->checkOut,
            'status' => $this->status
        ];
    }
}
