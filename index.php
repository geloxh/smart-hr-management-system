<?php
require_once 'vendor/autoload.php';

use Dotenv\Dotenv;
use Core\Http\Response;
use Modules\Employee\Controller\EmployeeController;
use Modules\Attendance\Controller\AttendanceController;
use Modules\Leave\Controller\LeaveController;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Remove base path if running in subdirectory
$uri = str_replace('/smart-hr-management-system', '', $uri);

// API Routes
switch (true) {
    case preg_match('/^\/api\/employees\/(\d+)$/', $uri, $matches):
        $controller = new EmployeeController();
        if ($method === 'GET') $controller->show((int)$matches[1]);
        break;
        
    case $uri === '/api/employees':
        $controller = new EmployeeController();
        if ($method === 'GET') $controller->index();
        elseif ($method === 'POST') $controller->store();
        break;
        
    case $uri === '/api/attendance':
        $controller = new AttendanceController();
        if ($method === 'GET') $controller->index();
        elseif ($method === 'POST') $controller->store();
        break;
        
    case $uri === '/api/leaves':
        $controller = new LeaveController();
        if ($method === 'GET') $controller->index();
        elseif ($method === 'POST') $controller->store();
        break;
        
    case $uri === '/' || $uri === '/dashboard':
        header('Location: /smart-hr-management-system/public/index.html');
        break;
        
    default:
        Response::error('Endpoint not found', 404);
}
