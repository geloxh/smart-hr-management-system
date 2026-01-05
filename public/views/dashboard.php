<?php
if (!isset($user)) {
    header('Location: /login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Smart HR</title>
    <link href="/smart-hr-management-system/public/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard">
        <header class="dashboard-header">
            <h1>Smart HR Management</h1>
            <div class="user-info">
                <span>Welcome, <?= htmlspecialchars($user->username) ?> (<?= ucfirst($user->role) ?>)</span>
                <a href="/logout" class="btn-logout">Logout</a>
            </div>
        </header>

        <nav class="dashboard-nav">
            <?php if ($user->isAdmin() || $user->isHR()): ?>
                <button onclick="showSection('employees')" class="nav-btn active">Employees</button>
                <button onclick="showSection('attendance')" class="nav-btn">Attendance</button>
                <button onclick="showSection('leaves')" class="nav-btn">Leave Management</button>
            <?php endif; ?>
            
            <?php if ($user->isEmployee()): ?>
                <button onclick="showSection('profile')" class="nav-btn active">My Profile</button>
                <button onclick="showSection('my-attendance')" class="nav-btn">My Attendance</button>
                <button onclick="showSection('my-leaves')" class="nav-btn">My Leaves</button>
            <?php endif; ?>
        </nav>

        <main class="dashboard-content">
            <?php if ($user->isAdmin() || $user->isHR()): ?>
                <section id="employees" class="section active">
                    <h2>Employee Management</h2>
                    <button onclick="showAddEmployeeForm()" class="btn-primary">Add Employee</button>
                    <div id="employee-list"></div>
                </section>

                <section id="attendance" class="section">
                    <h2>Attendance Management</h2>
                    <div id="attendance-report"></div>
                </section>

                <section id="leaves" class="section">
                    <h2>Leave Management</h2>
                    <div id="leave-requests"></div>
                </section>
            <?php endif; ?>

            <?php if ($user->isEmployee()): ?>
                <section id="profile" class="section active">
                    <h2>My Profile</h2>
                    <div id="profile-info"></div>
                </section>

                <section id="my-attendance" class="section">
                    <h2>My Attendance</h2>
                    <div class="attendance-controls">
                        <button onclick="checkIn()" class="btn-success">Check In</button>
                        <button onclick="checkOut()" class="btn-warning">Check Out</button>
                    </div>
                    <div id="my-attendance-history"></div>
                </section>

                <section id="my-leaves" class="section">
                    <h2>My Leave Requests</h2>
                    <button onclick="showLeaveForm()" class="btn-primary">Request Leave</button>
                    <div id="my-leave-list"></div>
                </section>
            <?php endif; ?>
        </main>
    </div>

    <script src="/smart-hr-management-system/public/js/dashboard.js"></script>
</body>
</html>
