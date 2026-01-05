function showSection(sectionId) {
    document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active');
    });
    
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    document.getElementById(sectionId).classList.add('active');
    event.target.classList.add('active');
    
    loadSectionData(sectionId);
}

function loadSectionData(sectionId) {
    switch(sectionId) {
        case 'employees':
            loadEmployees();
            break;
        case 'attendance':
            loadAttendanceReport();
            break;
        case 'leaves':
            loadLeaveRequests();
            break;
        case 'my-attendance':
            loadMyAttendance();
            break;
        case 'my-leaves':
            loadMyLeaves();
            break;
    }
}

async function loadEmployees() {
    showLoading('employee-list');
    try {
        const response = await fetch('/api/employees');
        const result = await response.json();
        
        if (result.success) {
            const html = result.data.map(emp => `
                <div class="employee-card">
                    <h3>${emp.first_name} ${emp.last_name}</h3>
                    <p><strong>ID:</strong> ${emp.employee_id}</p>
                    <p><strong>Department:</strong> ${emp.department}</p>
                    <p><strong>Position:</strong> ${emp.position}</p>
                    <p><strong>Email:</strong> ${emp.email}</p>
                    <div class="employee-actions">
                        <button onclick="editEmployee(${emp.id})" class="btn-edit">Edit</button>
                        <button onclick="deleteEmployee(${emp.id})" class="btn-delete">Delete</button>
                    </div>
                </div>
            `).join('');
            
            document.getElementById('employee-list').innerHTML = html;
        }
    } catch (error) {
        showError('employee-list', 'Failed to load employees');
    }
}

async function loadAttendanceReport() {
    showLoading('attendance-report');
    try {
        const response = await fetch('/api/attendance');
        const result = await response.json();
        
        if (result.success) {
            const html = `
                <table class="data-table">
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
                        ${result.data.map(att => `
                            <tr>
                                <td>${att.first_name} ${att.last_name}</td>
                                <td>${att.date}</td>
                                <td>${att.check_in || '-'}</td>
                                <td>${att.check_out || '-'}</td>
                                <td><span class="status-${att.status}">${att.status}</span></td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
            
            document.getElementById('attendance-report').innerHTML = html;
        }
    } catch (error) {
        showError('attendance-report', 'Failed to load attendance report');
    }
}

async function loadLeaveRequests() {
    showLoading('leave-requests');
    try {
        const response = await fetch('/api/leaves');
        const result = await response.json();
        
        if (result.success) {
            const html = result.data.map(leave => `
                <div class="leave-card">
                    <h4>${leave.first_name} ${leave.last_name}</h4>
                    <p><strong>Type:</strong> ${leave.type}</p>
                    <p><strong>Period:</strong> ${leave.start_date} to ${leave.end_date}</p>
                    <p><strong>Reason:</strong> ${leave.reason}</p>
                    <p><strong>Status:</strong> <span class="status-${leave.status}">${leave.status}</span></p>
                    ${leave.status === 'pending' ? `
                        <div class="leave-actions">
                            <button onclick="approveLeave(${leave.id})" class="btn-success">Approve</button>
                            <button onclick="rejectLeave(${leave.id})" class="btn-danger">Reject</button>
                        </div>
                    ` : ''}
                </div>
            `).join('');
            
            document.getElementById('leave-requests').innerHTML = html;
        }
    } catch (error) {
        showError('leave-requests', 'Failed to load leave requests');
    }
}

async function loadMyAttendance() {
    showLoading('my-attendance-history');
    try {
        const response = await fetch('/api/attendance?employee_only=true');
        const result = await response.json();
        
        if (result.success) {
            const html = `
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${result.data.map(att => `
                            <tr>
                                <td>${att.date}</td>
                                <td>${att.check_in || '-'}</td>
                                <td>${att.check_out || '-'}</td>
                                <td><span class="status-${att.status}">${att.status}</span></td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
            
            document.getElementById('my-attendance-history').innerHTML = html;
        }
    } catch (error) {
        showError('my-attendance-history', 'Failed to load attendance history');
    }
}

async function loadMyLeaves() {
    showLoading('my-leave-list');
    try {
        const response = await fetch('/api/leaves?employee_only=true');
        const result = await response.json();
        
        if (result.success) {
            const html = result.data.map(leave => `
                <div class="leave-card">
                    <p><strong>Type:</strong> ${leave.type}</p>
                    <p><strong>Period:</strong> ${leave.start_date} to ${leave.end_date}</p>
                    <p><strong>Reason:</strong> ${leave.reason}</p>
                    <p><strong>Status:</strong> <span class="status-${leave.status}">${leave.status}</span></p>
                </div>
            `).join('');
            
            document.getElementById('my-leave-list').innerHTML = html;
        }
    } catch (error) {
        showError('my-leave-list', 'Failed to load leave history');
    }
}

async function checkIn() {
    try {
        const response = await fetch('/api/attendance', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'check_in',
                csrf_token: getCSRFToken()
            })
        });
        
        const result = await response.json();
        if (result.success) {
            showSuccess('Checked in successfully!');
            loadMyAttendance();
        } else {
            showError(null, result.message);
        }
    } catch (error) {
        showError(null, 'Check-in failed');
    }
}

async function checkOut() {
    try {
        const response = await fetch('/api/attendance', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'check_out',
                csrf_token: getCSRFToken()
            })
        });
        
        const result = await response.json();
        if (result.success) {
            showSuccess('Checked out successfully!');
            loadMyAttendance();
        } else {
            showError(null, result.message);
        }
    } catch (error) {
        showError(null, 'Check-out failed');
    }
}

function showAddEmployeeForm() {
    const modal = createModal('Add Employee', `
        <form id="addEmployeeForm">
            <input type="hidden" name="csrf_token" value="${getCSRFToken()}">
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
    `);
    
    document.getElementById('addEmployeeForm').addEventListener('submit', handleAddEmployee);
}

function showLeaveForm() {
    const modal = createModal('Request Leave', `
        <form id="leaveRequestForm">
            <input type="hidden" name="csrf_token" value="${getCSRFToken()}">
            <div class="form-group">
                <label>Leave Type</label>
                <select name="type" required>
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
                <textarea name="reason" required></textarea>
            </div>
            <button type="submit" class="btn-primary">Submit Request</button>
        </form>
    `);
    
    document.getElementById('leaveRequestForm').addEventListener('submit', handleLeaveRequest);
}

async function handleAddEmployee(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('/api/employees', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        if (result.success) {
            closeModal();
            showSuccess('Employee added successfully!');
            loadEmployees();
        } else {
            showError(null, result.message);
        }
    } catch (error) {
        showError(null, 'Failed to add employee');
    }
}

async function handleLeaveRequest(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('/api/leaves', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        if (result.success) {
            closeModal();
            showSuccess('Leave request submitted successfully!');
            loadMyLeaves();
        } else {
            showError(null, result.message);
        }
    } catch (error) {
        showError(null, 'Failed to submit leave request');
    }
}

// Utility functions
function createModal(title, content) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>${title}</h3>
                <button onclick="closeModal()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                ${content}
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    return modal;
}

function closeModal() {
    const modal = document.querySelector('.modal');
    if (modal) modal.remove();
}

function showLoading(elementId) {
    if (elementId) {
        document.getElementById(elementId).innerHTML = '<div class="loading">Loading...</div>';
    }
}

function showError(elementId, message) {
    const errorHtml = `<div class="error-message">${message}</div>`;
    if (elementId) {
        document.getElementById(elementId).innerHTML = errorHtml;
    } else {
        alert(message);
    }
}

function showSuccess(message) {
    const notification = document.createElement('div');
    notification.className = 'notification success';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), 3000);
}

function getCSRFToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

// Load initial data
document.addEventListener('DOMContentLoaded', function() {
    const activeSection = document.querySelector('.section.active');
    if (activeSection) {
        loadSectionData(activeSection.id);
    }
});
