<?php
namespace Modules\Leave\Domain;

class Leave {
    public function __construct(
        public ?int $id = null,
        public ?int $employeeId = null,
        public ?string $startDate = null,
        public ?string $endDate = null,
        public ?string $type = null,
        public string $status = 'pending',
        public ?string $reason = null
    ) {}

    public function toArray(): array {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'type' => $this->type,
            'status' => $this->status,
            'reason' => $this->reason
        ];
    }
}
