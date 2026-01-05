<?php
session_start();

require_once 'vendor/autoload.php';
require_once 'Core/Middleware/SecurityMiddleware.php';
require_once 'Modules/Auth/Controller/AuthController.php';
require_once 'Modules/Auth/Controller/RegisterController.php';

use Dotenv\Dotenv;
use Core\Http\Response;
use Modules\Employee\Controller\EmployeeController;
use Modules\Attendance\Controller\AttendanceController;
use Modules\Leave\Controller\LeaveController;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Apply security middleware
SecurityMiddleware::handle();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$uri = str_replace('/smart-hr-management-system', '', $uri);

// Authentication Routes
switch (true) {
    case $uri === '/login':
        $controller = new AuthController();
        if ($method === 'GET') $controller->showLogin();
        elseif ($method === 'POST') $controller->login();
        break;
        
    case $uri === '/register':
        $controller = new RegisterController();
        if ($method === 'GET') $controller->showRegister();
        elseif ($method === 'POST') $controller->register();
        break;
        
    case $uri === '/logout':
        $controller = new AuthController();
        $controller->logout();
        break;
        
    case $uri === '/dashboard':
        $controller = new AuthController();
        $controller->dashboard();
        break;

    // API Routes (Protected)
    case preg_match('/^\/api\/employees\/(\d+)$/', $uri, $matches):
        if (!isAuthenticated()) { Response::error('Unauthorized', 401); break; }
        $controller = new EmployeeController();
        if ($method === 'GET') $controller->show((int)$matches[1]);
        elseif ($method === 'PUT') $controller->update((int)$matches[1]);
        elseif ($method === 'DELETE') $controller->delete((int)$matches[1]);
        break;
        
    case $uri === '/api/employees':
        if (!isAuthenticated()) { Response::error('Unauthorized', 401); break; }
        $controller = new EmployeeController();
        if ($method === 'GET') $controller->index();
        elseif ($method === 'POST') $controller->store();
        break;
        
    case $uri === '/api/attendance':
        if (!isAuthenticated()) { Response::error('Unauthorized', 401); break; }
        $controller = new AttendanceController();
        if ($method === 'GET') $controller->index();
        elseif ($method === 'POST') $controller->store();
        break;
        
    case $uri === '/api/leaves':
        if (!isAuthenticated()) { Response::error('Unauthorized', 401); break; }
        $controller = new LeaveController();
        if ($method === 'GET') $controller->index();
        elseif ($method === 'POST') $controller->store();
        break;
        
    case $uri === '/' || $uri === '':
        if (isAuthenticated()) {
            header('Location: /dashboard');
        } else {
            header('Location: /login');
        }
        break;
        
    default:
        Response::error('Endpoint not found', 404);
}

function isAuthenticated(): bool {
    return isset($_SESSION['user_id']) && isset($_SESSION['session_token']);
}
