<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - ShareTracker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/pages/login.css'])
</head>
<body>
    <div class="login-wrapper">
        <div class="login-hero">
            <div class="hero-icon" aria-hidden="true">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1>Welcome Back to ShareTracker</h1>
            <p>
                Access your analytics dashboard and track social engagement across all platforms in real-time.
            </p>
            <ul class="hero-features">
                <li>Real-time analytics and insights</li>
                <li>Multi-platform tracking</li>
                <li>Advanced filtering and reports</li>
                <li>Secure and encrypted data</li>
            </ul>
        </div>

        <div class="login-container">
            <div class="logo-section">
                <h2>Sign In</h2>
                <p>Enter your credentials to access the dashboard</p>
            </div>

            <div class="error-message" id="errorMessage"></div>

            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <span class="input-icon" aria-hidden="true"><i class="fas fa-envelope"></i></span>
                        <input type="email" id="email" name="email" placeholder="admin@example.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon" aria-hidden="true"><i class="fas fa-key"></i></span>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    Sign In
                </button>
            </form>

            <div class="test-credentials">
                <h3><i class="fas fa-lightbulb" aria-hidden="true"></i> Quick Login</h3>
                <button type="button" class="credential" onclick="fillCredentials('admin@example.com', 'password')" aria-label="Fill admin credentials">
                    <strong>Admin Account</strong>
                    <span>admin@example.com / password</span>
                </button>
                <button type="button" class="credential" onclick="fillCredentials('superadmin@example.com', 'password')" aria-label="Fill super admin credentials">
                    <strong>Super Admin Account</strong>
                    <span>superadmin@example.com / password</span>
                </button>
            </div>

            <div class="back-link">
                <a href="/"><i class="fas fa-arrow-left" aria-hidden="true"></i> Back to Demo Page</a>
            </div>
        </div>
    </div>

    <script>
        const API_URL = '/api';
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const errorMessage = document.getElementById('errorMessage');

        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }

        function showError(message) {
            errorMessage.textContent = message;
            errorMessage.classList.add('show');
            setTimeout(() => {
                errorMessage.classList.remove('show');
            }, 5000);
        }

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            loginBtn.disabled = true;
            loginBtn.innerHTML = '<span class="loading"></span>Signing in...';

            try {
                const response = await fetch(`${API_URL}/auth/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (data.success) {
                    localStorage.setItem('authToken', data.data.token);
                    localStorage.setItem('userData', JSON.stringify(data.data.user));

                    window.location.href = '/dashboard';
                } else {
                    showError(data.message || 'Login failed');
                    loginBtn.disabled = false;
                    loginBtn.innerHTML = 'Sign In';
                }
            } catch (error) {
                showError('Network error. Please try again.');
                loginBtn.disabled = false;
                loginBtn.innerHTML = 'Sign In';
            }
        });
    </script>
</body>
</html>
