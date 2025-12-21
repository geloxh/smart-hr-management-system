### HR Management System

```
app/
├── Core/
│   ├── Tenancy/
│   ├── Auth/
│   ├── Billing/
│   └── Audit/
│
├── Modules/
│   ├── Employee/
│   │   ├── Domain/
│   │   │   ├── Models/
│   │   │   ├── ValueObjects/
│   │   │   └── Events/
│   │   ├── Application/
│   │   │   ├── Services/
│   │   │   └── DTOs/
│   │   ├── Infrastructure/
│   │   │   └── Repositories/
│   │   └── Http/
│   │       ├── Controllers/
│   │       └── Requests/
│   │
│   ├── Attendance/
│   ├── Leave/
│   ├── Payroll/
│   ├── Recruitment/
│   └── Performance/
│
├── Shared/
│   ├── Helpers/
│   ├── Traits/
│   └── Enums/
```