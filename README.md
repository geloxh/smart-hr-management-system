### Smart HR Management System

```
smart-hr-management-system/
├── index.php                          # Entry point & router
├── composer.json                      # Dependencies
├── .env                              # Environment config
├── .htaccess                         # URL rewriting
│
├── Core/                             # Core system components
│   ├── Database/
│   │   └── Connection.php            # Database connection
│   ├── Repository/
│   │   └── BaseRepository.php        # Base repository pattern
│   └── Http/
│       └── Response.php              # HTTP response helper
│
├── Modules/                          # Business modules
│   ├── Employee/
│   │   ├── Domain/
│   │   │   └── Employee.php          # Employee entity
│   │   ├── Repository/
│   │   │   └── EmployeeRepository.php
│   │   ├── Service/
│   │   │   └── EmployeeService.php
│   │   └── Controller/
│   │       └── EmployeeController.php
│   │
│   ├── Attendance/
│   │   ├── Domain/
│   │   │   └── Attendance.php
│   │   ├── Repository/
│   │   │   └── AttendanceRepository.php
│   │   ├── Service/
│   │   │   └── AttendanceService.php
│   │   └── Controller/
│   │       └── AttendanceController.php
│   │
│   └── Leave/
│       ├── Domain/
│       │   └── Leave.php
│       ├── Repository/
│       │   └── LeaveRepository.php
│       ├── Service/
│       │   └── LeaveService.php
│       └── Controller/
│           └── LeaveController.php
│
├── database/
│   └── schema.sql                    # Database schema
│
└── public/                           # Frontend assets
    ├── css/
    ├── js/
    └── index.html

```