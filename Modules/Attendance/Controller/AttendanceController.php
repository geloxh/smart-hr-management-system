<?php
namespace Modules\Attendance\Controller;

use Core\Http\Response;
use Modules\Attendance\Service\AttendanceService;

class AttendanceController {
    public function __construct(
        private AttendanceService $service = new AttendanceService()
    ) {}

    public function index(): void {
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');
        
        $report = $this->service->getAttendanceReport($startDate, $endDate);
        Response::success($report);
    }

    public function store(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['action'] ?? '';
        $employeeId = $data['employee_id'] ?? 0;

        try {
            if ($action === 'check_in') {
                $result = $this->service->checkIn($employeeId);
                Response::success($result, 'Checked in successfully');
            } elseif ($action === 'check_out') {
                $this->service->checkOut($employeeId);
                Response::success([], 'Checked out successfully');
            } else {
                Response::error('Invalid action');
            }
        } catch (\Exception $e) {
            Response::error($e->getMessage());
        }
    }
}
