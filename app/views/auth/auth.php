<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBA Stats - Sign In</title>
    <link rel="stylesheet" href="../../../public/css/auth.css">
    <link href='https://fonts.googleapis.com/css?family=Big Shoulders Display' rel='stylesheet'>

</head>
<body>
    <?php include_once "../layout/header/header-default.php"?>

    <div class="background-animation"></div>
    <div class="top-auth-container">
        <div class="auth-container">
            <div class="auth-box sign-in" id="signInBox">
                <div class="logo">NBA STATS</div>
                <h2>Welcome Back</h2>
                <p class="subtitle">Access your NBA statistics dashboard</p>
                <form class="auth-form">
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" placeholder="Enter your password" required>
                        <span class="forgot-password">Forgot password?</span>
                    </div>
                    <button type="submit" class="auth-button">
                        <span>Sign In</span>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>
                </form>
                <p class="switch-text">
                    New to NBA Stats? 
                    <a href="#" onclick="toggleForms()">Create an account</a>
                </p>
            </div>

            <div class="auth-box sign-up hidden" id="signUpBox">
                <div class="logo">NBA STATS</div>
                <h2>Create Account</h2>
                <p class="subtitle">Join the NBA statistics community</p>
                <form class="auth-form">
                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" placeholder="Choose a username" required>
                    </div>
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" placeholder="Create a password" required>
                    </div>
                    <div class="input-group">
                        <label>Confirm Password</label>
                        <input type="password" placeholder="Confirm your password" required>
                    </div>
                    <button type="submit" class="auth-button">
                        <span>Create Account</span>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>
                </form>
                <p class="switch-text">
                    Already have an account? 
                    <a href="#" onclick="toggleForms()">Sign In</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function toggleForms() {
            const signInBox = document.getElementById('signInBox');
            const signUpBox = document.getElementById('signUpBox');
            signInBox.classList.toggle('hidden');
            signUpBox.classList.toggle('hidden');
        }
    </script>
</body>
</html>