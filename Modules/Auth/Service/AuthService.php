<?php

require_once __DIR__ . '/../Repository/UserRepository.php';
require_once __DIR__ . '/../Repository/SessionRepository.php';
require_once __DIR__ . '/PasswordService.php';

use Modules\Auth\Domain\User;
use Modules\Auth\Service\PasswordService;

class AuthService {
    private UserRepository $userRepo;
    private SessionRepository $sessionRepo;
    private PasswordService $passwordService;

    public function __construct() {
        $this->userRepo = new UserRepository();
        $this->sessionRepo = new SessionRepository();
        $this->passwordService = new PasswordService();
    }

    public function login(string $username, string $password): ?array {
        $user = $this->userRepo->findByUsername($username);
        
        if (!$user || !$this->passwordService->verify($password, $user->password)) {
            return null;
        }

        $token = bin2hex(random_bytes(32));
        $session = $this->sessionRepo->create($user->id, $token);

        return [
            'user' => $user,
            'token' => $token,
            'session' => $session
        ];
    }

    public function register(array $userData): ?User {
        if ($this->userRepo->emailExists($userData['email']) || 
            $this->userRepo->usernameExists($userData['username'])) {
            return null;
        }

        $userData['password'] = $this->passwordService->hash($userData['password']);
        return $this->userRepo->create($userData);
    }

    public function logout(string $sessionToken): bool {
        return $this->sessionRepo->deleteByToken($sessionToken);
    }

    public function validateSession(string $token): ?User {
        $session = $this->sessionRepo->findByToken($token);
        
        if (!$session || $session->isExpired()) {
            return null;
        }

        return $this->userRepo->findById($session->user_id);
    }

    public function hasPermission(User $user, string $permission): bool {
        $permissions = [
            'admin' => ['*'],
            'hr' => ['employee.view', 'employee.edit', 'attendance.view', 'leave.approve'],
            'employee' => ['profile.view', 'profile.edit', 'attendance.own', 'leave.request']
        ];

        $userPermissions = $permissions[$user->role] ?? [];
        return in_array('*', $userPermissions) || in_array($permission, $userPermissions);
    }
}
