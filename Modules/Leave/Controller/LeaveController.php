<?php
namespace Modules\Leave\Controller;

use Core\Http\Response;
use Core\Validation\Validator;
use Modules\Leave\Service\LeaveService;

class LeaveController {
    public function __construct(
        private LeaveService $service = new LeaveService()
    ) {}

    public function index(): void {
        $leaves = $this->service->getPendingLeaves();
        Response::success($leaves);
    }

    public function store(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $validator = new Validator();
        $rules = [
            'employee_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'type' => 'required',
            'reason' => 'required'
        ];

        if (!$validator->validate($data, $rules)) {
            Response::error('Validation failed', 422);
            return;
        }

        $id = $this->service->requestLeave($data);
        Response::success(['id' => $id], 'Leave request submitted successfully');
    }
}
