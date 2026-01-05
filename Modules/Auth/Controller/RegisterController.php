<?php

require_once __DIR__ . '/../Service/AuthService.php';
require_once __DIR__ . '/../../../Core/Http/Response.php';

class RegisterController {
    private AuthService $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function showRegister() {
        include __DIR__ . '/../../../public/views/register.php';
    }

    public function register() {
        $errors = $this->validateInput();
        
        if (!empty($errors)) {
            Response::json(['success' => false, 'errors' => $errors], 400);
            return;
        }

        $userData = [
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'password' => $_POST['password'],
            'role' => $_POST['role'] ?? 'employee'
        ];

        $user = $this->authService->register($userData);

        if ($user) {
            Response::json([
                'success' => true, 
                'message' => 'Registration successful',
                'redirect' => '/login'
            ]);
        } else {
            Response::json([
                'success' => false, 
                'message' => 'Username or email already exists'
            ], 409);
        }
    }

    private function validateInput(): array {
        $errors = [];

        if (empty($_POST['username'])) {
            $errors['username'] = 'Username is required';
        } elseif (strlen($_POST['username']) < 3) {
            $errors['username'] = 'Username must be at least 3 characters';
        }

        if (empty($_POST['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        if (empty($_POST['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($_POST['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (empty($_POST['confirm_password'])) {
            $errors['confirm_password'] = 'Password confirmation is required';
        } elseif ($_POST['password'] !== $_POST['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match';
        }

        return $errors;
    }
}
