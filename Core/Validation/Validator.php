<?php
namespace Core\Validation;

class Validator {
    private array $errors = [];

    public function validate(array $data, array $rules): bool {
        foreach ($rules as $field => $rule) {
            $this->validateField($field, $data[$field] ?? null, $rule);
        }
        return empty($this->errors);
    }

    private function validateField(string $field, $value, string $rules): void {
        $ruleList = explode('|', $rules);
        
        foreach ($ruleList as $rule) {
            if ($rule === 'required' && empty($value)) {
                $this->errors[$field][] = "$field is required";
            }
            if (str_starts_with($rule, 'email') && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field][] = "$field must be a valid email";
            }
        }
    }

    public function getErrors(): array {
        return $this->errors;
    }
}