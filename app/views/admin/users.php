<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Big Shoulders Display' rel='stylesheet'>
    <title>Admin Panel - NBA Stats</title>
    <link rel="stylesheet" href="/fin_proj/public/css/admin.css">
    <link rel="shortcut icon" href="/fin_proj/public/images/nba-logo.png" type="image/x-icon">
</head>
<body>
    <?php require_once __DIR__ . "/../layout/header/header-logged.php"; ?>

    <main class="main-content">
        <div class="container">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <section>
                <h2 class="section-title">Users</h2>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Admin</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td>
                                            <?php echo $user['is_admin'] ? 'Yes' : 'No'; ?>
                                        </td>
                                        <td>
                                            <a href="/fin_proj/admin/edit/<?php echo $user['id']; ?>" 
                                               class="btn btn-primary btn-sm">
                                                Edit
                                            </a>
                                            <a href="/fin_proj/admin/delete/<?php echo $user['id']; ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Are you sure you want to delete this user?');">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="text-center mt-4">
                <a href="/fin_proj/home" class="btn btn-secondary">
                    Go home
                </a>
            </div>
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