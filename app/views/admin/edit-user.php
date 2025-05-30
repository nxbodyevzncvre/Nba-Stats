<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Big Shoulders Display' rel='stylesheet'>
    <title>Edit User - NBA Stats</title>
    <link rel="stylesheet" href="/fin_proj/public/css/admin.css">
    <link rel="shortcut icon" href="/fin_proj/public/images/nba-logo.png" type="image/x-icon">
</head>
<body>
    <?php require_once __DIR__ . "/../layout/header/header-logged.php"; ?>

    <main class="main-content">
        <div class="container">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>

            <section>
                <h2 class="section-title">Edit User</h2>
                
                <div class="form-container">
                    <form method="POST" action="/fin_proj/admin/update/<?php echo htmlspecialchars($data['editUser']['id']); ?>" class="edit-user-form">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" 
                                id="username" 
                                name="username" 
                                value="<?php echo htmlspecialchars($data['editUser']['username'] ?? ''); ?>" 
                                required 
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="password">New Password (leave empty to keep current):</label>
                            <input type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-control"
                                    placeholder="Enter new password or leave empty">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Update User</button>
                            <a href="/fin_proj/admin/" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <?php require_once __DIR__ . "/../layout/footer/footer-logged.php"; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const closeMenuButton = document.querySelector('.close-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');

            if (mobileMenuButton && mobileMenu && closeMenuButton) {
                mobileMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.add('active');
                    mobileMenu.classList.remove('hidden');
                });

                closeMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.remove('active');
                    mobileMenu.classList.add('hidden');
                });
            }

            const avatar = document.getElementById('avatar');
            const dropdownMenu = document.getElementById('dropdown-menu');

            if (avatar && dropdownMenu) {
                avatar.addEventListener('click', () => {
                    dropdownMenu.classList.toggle('active');
                });

                document.addEventListener('click', (event) => {
                    if (!avatar.contains(event.target) && !dropdownMenu.contains(event.target)) {
                        dropdownMenu.classList.remove('active');
                    }
                });
            }
        });
    </script>
</body>
</html>