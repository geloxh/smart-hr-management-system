<?php

namespace Modules\Auth\Domain;

class Session {
    public int $id;
    public int $user_id;
    public string $session_token;
    public string $expires_at;
    public string $created_at;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->id = $data['id'] ?? 0;
            $this->user_id = $data['user_id'] ?? 0;
            $this->session_token = $data['session_token'] ?? '';
            $this->expires_at = $data['expires_at'] ?? '';
            $this->created_at = $data['created_at'] ?? '';
        }
    }

    public function isExpired(): bool {
        return strtotime($this->expires_at) < time();
    }

    public function generateToken(): string {
        return bin2hex(random_bytes(32));
    }
}
