<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBA Stats - Sign In</title>
    <link rel="stylesheet" href="/fin_proj/public/css/auth.css">
    <link href='https://fonts.googleapis.com/css?family=Big Shoulders Display' rel='stylesheet'>
</head>
<body>
    <?php include_once __DIR__ . "/../layout/header/header-default.php"?>

    <div class="background-animation"></div>
    <div class="top-auth-container">
        <div class="auth-container">

            <div class="auth-box sign-in <?php echo isset($_GET['action']) && $_GET['action'] === 'register' ? 'hidden' : ''; ?>" id="signInBox">
                <div class="logo">NBA STATS</div>
                <h2>Welcome Back</h2>
                <p class="subtitle">Access your NBA statistics dashboard</p>
                
                <?php if (isset($_SESSION['login_errors']['general'])): ?>
                    <div class="error-message" style="color:red"><?php echo $_SESSION['login_errors']['general']; ?></div>
                <?php endif; ?>
                
                <form class="auth-form" action="/fin_proj/login" method="post">
                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Enter your username" value="<?php echo htmlspecialchars($_SESSION['login_data']['username'] ?? ''); ?>" required>
                        <?php if (isset($_SESSION['login_errors']['username'])): ?>
                            <div class="error-message"><?php echo $_SESSION['login_errors']['username']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter your password" required>
                        <?php if (isset($_SESSION['login_errors']['password'])): ?>
                            <div class="error-message"><?php echo $_SESSION['login_errors']['password']; ?></div>
                        <?php endif; ?>
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


            <div class="auth-box sign-up <?php echo isset($_GET['action']) && $_GET['action'] === 'register' ? '' : 'hidden'; ?>" id="signUpBox">
                <div class="logo">NBA STATS</div>
                <h2>Create Account</h2>
                <p class="subtitle">Join the NBA statistics community</p>
                
                <?php if (isset($_SESSION['register_errors']['general'])): ?>
                    <div class="error-message"><?php echo $_SESSION['register_errors']['general']; ?></div>
                <?php endif; ?>
                
                <form class="auth-form" action="/fin_proj/register" method="post">
                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Choose a username" value="<?php echo htmlspecialchars($_SESSION['register_data']['username'] ?? ''); ?>" required>
                        <?php if (isset($_SESSION['register_errors']['username'])): ?>
                            <div class="error-message"><?php echo $_SESSION['register_errors']['username']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Create a password" required>
                        <?php if (isset($_SESSION['register_errors']['password'])): ?>
                            <div class="error-message"><?php echo $_SESSION['register_errors']['password']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="input-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                        <?php if (isset($_SESSION['register_errors']['confirm_password'])): ?>
                            <div class="error-message"><?php echo $_SESSION['register_errors']['confirm_password']; ?></div>
                        <?php endif; ?>
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
            
            const currentAction = signInBox.classList.contains('hidden') ? 'register' : 'login';
            history.pushState({}, '', '/auth?action=' + currentAction);
        }
        

        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['register_errors'])): ?>
                document.getElementById('signInBox').classList.add('hidden');
                document.getElementById('signUpBox').classList.remove('hidden');
            <?php endif; ?>
        });
    </script>
    
    <?php
    unset($_SESSION['login_errors']);
    unset($_SESSION['login_data']);
    unset($_SESSION['register_errors']);
    unset($_SESSION['register_data']);
    ?>
</body>
</html>