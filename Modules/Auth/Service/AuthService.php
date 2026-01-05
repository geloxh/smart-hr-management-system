<?php

class AuthService {
    private int $maxLoginAttempts = 5;
    private int $lockoutTime = 900; // 15 minutes

    public function login(string $username, string $password): ?array {
        // Check if account is locked
        if ($this->isAccountLocked($username)) {
            return null;
        }

        $user = $this->userRepo->findByUsername($username);
        
        if (!$user || !$this->passwordService->verify($password, $user->password)) {
            $this->recordFailedAttempt($username);
            return null;
        }

        // Clear failed attempts on successful login
        $this->clearFailedAttempts($username);
        
        $token = bin2hex(random_bytes(32));
        $session = $this->sessionRepo->create($user->id, $token);

        // Log successful login
        $this->logSecurityEvent('login_success', $user->id);

        return [
            'user' => $user,
            'token' => $token,
            'session' => $session
        ];
    }

    private function isAccountLocked(string $username): bool {
        $attempts = $_SESSION['login_attempts'][$username] ?? [];
        $recentAttempts = array_filter($attempts, fn($time) => $time > (time() - $this->lockoutTime));
        
        return count($recentAttempts) >= $this->maxLoginAttempts;
    }

    private function recordFailedAttempt(string $username): void {
        $_SESSION['login_attempts'][$username][] = time();
        $this->logSecurityEvent('login_failed', null, ['username' => $username]);
    }

    private function clearFailedAttempts(string $username): void {
        unset($_SESSION['login_attempts'][$username]);
    }

    private function logSecurityEvent(string $event, ?int $userId, array $data = []): void {
        // Log to database or file
        error_log(json_encode([
            'event' => $event,
            'user_id' => $userId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'timestamp' => date('Y-m-d H:i:s'),
            'data' => $data
        ]));
    }
}
