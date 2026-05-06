<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Church System | Create Account</title>
    <style>
        body { background: #0f172a; display: flex; align-items: center; justify-content: center; height: 100vh; font-family: sans-serif; margin: 0; }
        .card { background: #1e293b; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; border: 1px solid #334155; color: white; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; background: #0f172a; border: 1px solid #334155; color: white; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .msg { padding: 10px; border-radius: 6px; margin-bottom: 10px; font-size: 14px; text-align: center; }
        .error { background: rgba(248, 113, 113, 0.2); color: #f87171; }
        .success { background: rgba(34, 197, 94, 0.2); color: #4ade80; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="text-align: center;">Create Admin Account</h2>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="msg error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="msg success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form action="../api/auth_register.php" method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role">
                <option value="user">Standard User</option>
                <option value="admin">Administrator</option>
                <option value="super_admin">Super Admin</option>
            </select>
            <button type="submit">Register User</button>
        </form>
        <p style="text-align: center; font-size: 13px; color: #94a3b8;">
            Already have an account? <a href="login.php" style="color: #3b82f6;">Login</a>
        </p>
    </div>
</body>
</html>