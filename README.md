### Smart HR Management System
A progressive, scalable and maintainable HR management system built with PHP.

### Project Structure
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

## Features

- **Employee Management**: Complete employee lifecycle management
- **Attendance Tracking**: Real-time attendance monitoring and reporting
- **Leave Management**: Leave request, approval, and tracking system
- **Modular Architecture**: Clean separation of concerns with domain-driven design
- **RESTful API**: Well-structured API endpoints for all operations

## Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Composer
- Apache/Nginx web server

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd smart-hr-management-system

<div align="center">

**geloxh**

</div>