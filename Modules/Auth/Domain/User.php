<?php

namespace Modules\Auth\Domain;

class User {
    public int $id;
    public string $username;
    public string $email;
    public string $password;
    public string $role;
    public ?int $employee_id;
    public bool $is_active;
    public string $created_at;
    public string $updated_at;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->id = $data['id'] ?? 0;
            $this->username = $data['username'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->password = $data['password'] ?? '';
            $this->role = $data['role'] ?? 'employee';
            $this->employee_id = $data['employee_id'] ?? null;
            $this->is_active = $data['is_active'] ?? true;
            $this->created_at = $data['created_at'] ?? '';
            $this->updated_at = $data['updated_at'] ?? '';
        }
    }

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public function isHR(): bool {
        return $this->role === 'hr';
    }

    public function isEmployee(): bool {
        return $this->role === 'employee';
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            'employee_id' => $this->employee_id,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
