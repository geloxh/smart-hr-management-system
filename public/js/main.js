const API_BASE = '/smart-hr-management-system/api';

// Navigation
function showSection(sectionName) {
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
    
    document.getElementById(sectionName).classList.add('active');
    event.target.classList.add('active');
    
    if (sectionName === 'employees') loadEmployees();
    if (sectionName === 'attendance') loadAttendanceReport();
    if (sectionName === 'leaves') loadLeaves();
}

// Employee Management
async function loadEmployees() {
    try {
        const response = await fetch(`${API_BASE}/employees`);
        const data = await response.json();
        
        if (data.success) {
            displayEmployees(data.data);
        }
    } catch (error) {
        showAlert('Error loading employees', 'error');
    }
}

function displayEmployees(employees) {
    const html = `
        <table class="table">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                ${employees.map(emp => `
                    <tr>
                        <td>${emp.employee_id}</td>
                        <td>${emp.first_name} ${emp.last_name}</td>
                        <td>${emp.email}</td>
                        <td>${emp.position}</td>
                        <td>${emp.department}</td>
                        <td>${emp.status}</td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
    document.getElementById('employee-list').innerHTML = html;
}

function showAddEmployeeForm() {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close" onclick="this.parentElement.parentElement.remove()">&times;</span>
            <h3>Add New Employee</h3>
            <form id="employee-form">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Position</label>
                    <input type="text" name="position" required>
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <input type="text" name="department" required>
                </div>
                <div class="form-group">
                    <label>Hire Date</label>
                    <input type="date" name="hire_date" required>
                </div>
                <button type="submit" class="btn-primary">Add Employee</button>
            </form>
        </div>
    `;
    
    document.body.appendChild(modal);
    modal.style.display = 'block';
    
    document.getElementById('employee-form').onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);
        
        try {
            const response = await fetch(`${API_BASE}/employees`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            if (result.success) {
                showAlert('Employee added successfully', 'success');
                modal.remove();
                loadEmployees();
            } else {
                showAlert('Error adding employee', 'error');
            }
        } catch (error) {
            showAlert('Error adding employee', 'error');
        }
    };
}

// Attendance Management
async function checkIn() {
    const employeeId = document.getElementById('employee-id').value;
    if (!employeeId) {
        showAlert('Please enter employee ID', 'error');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}/attendance`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'check_in', employee_id: parseInt(employeeId)})
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert('Checked in successfully', 'success');
            loadAttendanceReport();
        } else {
            showAlert(result.message, 'error');
        }
    } catch (error) {
        showAlert('Error checking in', 'error');
    }
}

async function checkOut() {
    const employeeId = document.getElementById('employee-id').value;
    if (!employeeId) {
        showAlert('Please enter employee ID', 'error');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}/attendance`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'check_out', employee_id: parseInt(employeeId)})
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert('Checked out successfully', 'success');
            loadAttendanceReport();
        } else {
            showAlert(result.message, 'error');
        }
    } catch (error) {
        showAlert('Error checking out', 'error');
    }
}

async function loadAttendanceReport() {
    try {
        const response = await fetch(`${API_BASE}/attendance`);
        const data = await response.json();
        
        if (data.success) {
            displayAttendanceReport(data.data);
        }
    } catch (error) {
        showAlert('Error loading attendance report', 'error');
    }
}

function displayAttendanceReport(attendance) {
    const html = `
        <table class="table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                ${attendance.map(att => `
                    <tr>
                        <td>${att.first_name} ${att.last_name} (${att.employee_id})</td>
                        <td>${att.date}</td>
                        <td>${att.check_in || '-'}</td>
                        <td>${att.check_out || '-'}</td>
                        <td>${att.status}</td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
    document.getElementById('attendance-report').innerHTML = html;
}

// Leave Management
async function loadLeaves() {
    try {
        const response = await fetch(`${API_BASE}/leaves`);
        const data = await response.json();
        
        if (data.success) {
            displayLeaves(data.data);
        }
    } catch (error) {
        showAlert('Error loading leaves', 'error');
    }
}

function displayLeaves(leaves) {
    const html = `
        <table class="table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                ${leaves.map(leave => `
                    <tr>
                        <td>${leave.first_name} ${leave.last_name} (${leave.employee_id})</td>
                        <td>${leave.type}</td>
                        <td>${leave.start_date}</td>
                        <td>${leave.end_date}</td>
                        <td>${leave.status}</td>
                        <td>${leave.reason}</td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
    document.getElementById('leave-list').innerHTML = html;
}

function showLeaveForm() {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close" onclick="this.parentElement.parentElement.remove()">&times;</span>
            <h3>Request Leave</h3>
            <form id="leave-form">
                <div class="form-group">
                    <label>Employee ID</label>
                    <input type="number" name="employee_id" required>
                </div>
                <div class="form-group">
                    <label>Leave Type</label>
                    <select name="type" required>
                        <option value="">Select Type</option>
                        <option value="sick">Sick Leave</option>
                        <option value="vacation">Vacation</option>
                        <option value="personal">Personal Leave</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" required>
                </div>
                <div class="form-group">
                    <label>Reason</label>
                    <textarea name="reason" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn-primary">Submit Request</button>
            </form>
        </div>
    `;
    
    document.body.appendChild(modal);
    modal.style.display = 'block';
    
    document.getElementById('leave-form').onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);
        data.employee_id = parseInt(data.employee_id);
        
        try {
            const response = await fetch(`${API_BASE}/leaves`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            if (result.success) {
                showAlert('Leave request submitted successfully', 'success');
                modal.remove();
                loadLeaves();
            } else {
                showAlert('Error submitting leave request', 'error');
            }
        } catch (error) {
            showAlert('Error submitting leave request', 'error');
        }
    };
}

// Utility Functions
function showAlert(message, type) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    
    document.querySelector('.container').insertBefore(alert, document.querySelector('main'));
    
    setTimeout(() => alert.remove(), 3000);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadEmployees();
});
