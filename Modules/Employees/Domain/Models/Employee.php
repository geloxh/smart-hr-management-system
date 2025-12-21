<?php

namespace Modules\Employees\Domain\Models;

class Employee {
    private string $id;
    private string $name;
    private string $email;
    private string $position;

    public function __construct(string $id, string $name, string $email, string $position) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->position = $position;
    }
}