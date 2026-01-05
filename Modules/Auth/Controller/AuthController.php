<?php

require_once __DIR__ . '/../Service/AuthService.php';
require_once __DIR__ . '/../../../Core/Http/Response.php';

class AuthController {
    private AuthService $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function showLogin() {
        if ($this->isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }
        include __DIR__ . '/../../../public/views/login.php';
    }

    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->authService->login($username, $password);

        if ($result) {
            $_SESSION['user_id'] = $result['user']->id;
            $_SESSION['session_token'] = $result['token'];
            $_SESSION['user_role'] = $result['user']->role;

            Response::json(['success' => true, 'redirect' => '/dashboard']);
        } else {
            Response::json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }
    }

    public function showRegister() {
        include __DIR__ . '/../../../public/views/register.php';
    }

    public function register() {
        $userData = [
            'username' => $_POST['username'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'role' => 'employee'
        ];

        $user = $this->authService->register($userData);

        if ($user) {
            Response::json(['success' => true, 'message' => 'Registration successful']);
        } else {
            Response::json(['success' => false, 'message' => 'Registration failed'], 400);
        }
    }

    public function logout() {
        if (isset($_SESSION['session_token'])) {
            $this->authService->logout($_SESSION['session_token']);
        }
        
        session_destroy();
        header('Location: /login');
        exit;
    }

    public function dashboard() {
        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }

        $user = $this->getCurrentUser();
        include __DIR__ . '/../../../public/views/dashboard.php';
    }

    private function isLoggedIn(): bool {
        return isset($_SESSION['session_token']) && 
               $this->authService->validateSession($_SESSION['session_token']);
    }

    private function getCurrentUser(): ?User {
        if (!isset($_SESSION['session_token'])) {
            return null;
        }
        return $this->authService->validateSession($_SESSION['session_token']);
    }
}
