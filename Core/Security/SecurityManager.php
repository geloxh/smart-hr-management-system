<?php

class SecurityManager {
    
    public static function generateCSRFToken(): string {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        $_SESSION['csrf_expires'] = time() + 3600;
        return $token;
    }

    public static function validateCSRFToken(string $token): bool {
        return isset($_SESSION['csrf_token']) && 
               isset($_SESSION['csrf_expires']) &&
               $_SESSION['csrf_expires'] > time() &&
               hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function sanitizeInput(string $input): string {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public static function validateEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function setSecurityHeaders(): void {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        header('Content-Security-Policy: default-src \'self\'');
    }
}
