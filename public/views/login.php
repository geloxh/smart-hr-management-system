<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart HR</title>
    <link href="/smart-hr-management-system/public/css/auth.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h2>Login to Smart HR</h2>
            <form id="loginForm">
                <input type="hidden" name="csrf_token" value="<?= SecurityManager::generateCSRFToken() ?>">
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-primary">Login</button>
                
                <div class="auth-links">
                    <a href="/register">Don't have an account? Register</a>
                </div>
            </form>
            
            <div id="message" class="message"></div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    window.location.href = result.redirect || '/dashboard';
                } else {
                    document.getElementById('message').innerHTML = 
                        `<div class="error">${result.message}</div>`;
                }
            } catch (error) {
                document.getElementById('message').innerHTML = 
                    `<div class="error">Login failed. Please try again.</div>`;
            }
        });
    </script>
</body>
</html>
