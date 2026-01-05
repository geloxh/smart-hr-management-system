<?php

namespace Modules\Auth\Service;

class PasswordService {
    
    public function hash(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verify(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public function isStrong(string $password): bool {
        return strlen($password) >= 8 && 
               preg_match('/[A-Z]/', $password) && 
               preg_match('/[a-z]/', $password) && 
               preg_match('/[0-9]/', $password);
    }

    public function generateRandom(int $length = 12): string {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        return substr(str_shuffle(str_repeat($chars, ceil($length / strlen($chars)))), 0, $length);
    }
}
