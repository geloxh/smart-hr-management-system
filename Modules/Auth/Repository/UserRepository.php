<?php

require_once __DIR__ . '/../../../Core/Repository/BaseRepository.php';
require_once __DIR__ . '/../Domain/User.php';

use Modules\Auth\Domain\User;

class UserRepository extends BaseRepository {
    
    public function findByUsername(string $username): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? AND is_active = 1");
        $stmt->execute([$username]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new User($data) : null;
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new User($data) : null;
    }

    public function findById(int $id): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new User($data) : null;
    }

    public function create(array $userData): User {
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password, role, employee_id) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $userData['username'],
            $userData['email'],
            $userData['password'],
            $userData['role'] ?? 'employee',
            $userData['employee_id'] ?? null
        ]);

        $userId = $this->db->lastInsertId();
        return $this->findById($userId);
    }

    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function usernameExists(string $username): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }
}
