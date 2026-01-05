<?php

class RateLimiter {
    private static array $requests = [];

    public static function checkLimit(string $ip, int $maxRequests = 100, int $window = 3600): bool {
        $now = time();
        $key = $ip . '_' . floor($now / $window);
        
        if (!isset(self::$requests[$key])) {
            self::$requests[$key] = 0;
        }
        
        self::$requests[$key]++;
        
        return self::$requests[$key] <= $maxRequests;
    }

    public static function blockIP(string $ip): void {
        file_put_contents('blocked_ips.txt', $ip . "\n", FILE_APPEND);
    }
}
