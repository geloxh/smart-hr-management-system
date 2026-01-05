<?php
namespace Modules\Employee\Controller;

use Core\Http\Response;
use Core\Validation\Validator;
use Modules\Employee\Service\EmployeeService;

class EmployeeController {
    public function __construct(
        private EmployeeService $service = new EmployeeService()
    ) {}

    public function index(): void {
        $employees = $this->service->getAllEmployees();
        Response::success($employees);
    }

    public function store(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $validator = new Validator();
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'position' => 'required',
            'department' => 'required',
            'hire_date' => 'required'
        ];

        if (!$validator->validate($data, $rules)) {
            Response::error('Validation failed', 422);
            return;
        }

        $id = $this->service->createEmployee($data);
        Response::success(['id' => $id], 'Employee created successfully');
    }

    public function show(int $id): void {
        $employee = $this->service->getEmployee($id);
        if (!$employee) {
            Response::error('Employee not found', 404);
            return;
        }
        Response::success($employee);
    }
}
