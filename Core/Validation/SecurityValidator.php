<?php

class SecurityValidator {
    
    public static function validateInput(array $data, array $rules): array {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            
            if (isset($rule['required']) && $rule['required'] && empty($value)) {
                $errors[$field] = "$field is required";
                continue;
            }
            
            if (!empty($value)) {
                // SQL injection protection
                if (isset($rule['no_sql']) && self::containsSQLInjection($value)) {
                    $errors[$field] = "Invalid characters detected";
                }
                
                // XSS protection
                if (isset($rule['no_xss']) && self::containsXSS($value)) {
                    $errors[$field] = "Invalid content detected";
                }
                
                // Length validation
                if (isset($rule['min_length']) && strlen($value) < $rule['min_length']) {
                    $errors[$field] = "$field must be at least {$rule['min_length']} characters";
                }
                
                if (isset($rule['max_length']) && strlen($value) > $rule['max_length']) {
                    $errors[$field] = "$field must not exceed {$rule['max_length']} characters";
                }
            }
        }
        
        return $errors;
    }
    
    private static function containsSQLInjection(string $input): bool {
        $patterns = [
            '/(\bUNION\b|\bSELECT\b|\bINSERT\b|\bDELETE\b|\bUPDATE\b|\bDROP\b)/i',
            '/(\bOR\b|\bAND\b)\s+\d+\s*=\s*\d+/i',
            '/[\'";]/',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
    
    private static function containsXSS(string $input): bool {
        $patterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/javascript:/i',
            '/on\w+\s*=/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
}
