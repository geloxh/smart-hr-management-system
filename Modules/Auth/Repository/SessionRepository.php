<?php

require_once __DIR__ . '/../../../Core/Repository/BaseRepository.php';
require_once __DIR__ . '/../Domain/Session.php';

use Modules\Auth\Domain\Session;

class SessionRepository extends BaseRepository {
    
    public function create(int $userId, string $token, int $expiresInHours = 24): Session {
        $expiresAt = date('Y-m-d H:i:s', time() + ($expiresInHours * 3600));
        
        $stmt = $this->db->prepare("
            INSERT INTO user_sessions (user_id, session_token, expires_at) 
            VALUES (?, ?, ?)
        ");
        
        $stmt->execute([$userId, $token, $expiresAt]);
        
        return new Session([
            'id' => $this->db->lastInsertId(),
            'user_id' => $userId,
            'session_token' => $token,
            'expires_at' => $expiresAt,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function findByToken(string $token): ?Session {
        $stmt = $this->db->prepare("SELECT * FROM user_sessions WHERE session_token = ?");
        $stmt->execute([$token]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new Session($data) : null;
    }

    public function deleteByToken(string $token): bool {
        $stmt = $this->db->prepare("DELETE FROM user_sessions WHERE session_token = ?");
        return $stmt->execute([$token]);
    }

    public function deleteExpiredSessions(): int {
        $stmt = $this->db->prepare("DELETE FROM user_sessions WHERE expires_at < NOW()");
        $stmt->execute();
        return $stmt->rowCount();
    }
}
