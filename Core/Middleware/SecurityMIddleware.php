<?php

class Encryption {
    private static string $key;

    public static function setKey(string $key): void {
        self::$key = $key;
    }

    public static function encrypt(string $data): string {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', self::$key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt(string $data): string {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', self::$key, 0, $iv);
    }
}
