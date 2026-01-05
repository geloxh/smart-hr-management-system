<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Smart HR</title>
    <link href="/smart-hr-management-system/public/css/auth.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h2>Register for Smart HR</h2>
            <form id="registerForm">
                <input type="hidden" name="csrf_token" value="<?= SecurityManager::generateCSRFToken() ?>">
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn-primary">Register</button>
                
                <div class="auth-links">
                    <a href="/login">Already have an account? Login</a>
                </div>
            </form>
            
            <div id="message" class="message"></div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    document.getElementById('message').innerHTML = 
                        `<div class="success">${result.message}</div>`;
                    setTimeout(() => window.location.href = '/login', 2000);
                } else {
                    let errorMsg = result.message || 'Registration failed';
                    if (result.errors) {
                        errorMsg = Object.values(result.errors).join('<br>');
                    }
                    document.getElementById('message').innerHTML = 
                        `<div class="error">${errorMsg}</div>`;
                }
            } catch (error) {
                document.getElementById('message').innerHTML = 
                    `<div class="error">Registration failed. Please try again.</div>`;
            }
        });
    </script>
</body>
</html>
