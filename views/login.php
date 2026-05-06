<?php 
session_start(); 
// Redirect if already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church System | Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { 
            background-image: url("images/background.jpg");
            background-color: black;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
            font-family: 'Inter', sans-serif; 
            margin: 0;
        }
        .login-card { 
            background: #1e293b; 
            padding: 40px; 
            border-radius: 16px; 
            width: 90%; 
            max-width: 400px; 
            border: 1px solid #334155; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;

        }
        h2 { color: white; text-align: center; margin-bottom: 1.5rem; font-weight: 700; }
        label { color: #94a3b8; font-size: 0.875rem; margin-bottom: 0.5rem; display: block; }
        input { 
            width: 100%; 
            padding: 12px; 
            margin-bottom: 1.2rem; 
            background: #0f172a; 
            border: 1px solid #334155; 
            color: white; 
            border-radius: 8px; 
            box-sizing: border-box; /* Prevents input from overflowing the card */
        }
        input:focus { outline: none; border-color: #3b82f6; ring: 2px #3b82f6; }
        button { 
            width: 100%; 
            padding: 12px; 
            background: #3b82f6; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            font-weight: bold; 
            cursor: pointer; 
            transition: background 0.2s;
        }
        button:hover { background: #2563eb; }
        .error { 
            background: rgba(248, 113, 113, 0.1);
            color: #f87171; 
            font-size: 0.875rem; 
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 1.5rem; 
            text-align: center; 
            border: 1px solid rgba(248, 113, 113, 0.2);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Welcome Back</h2>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="error">
                <?= htmlspecialchars($_SESSION['error']); ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <form action="../api/auth_login.php" method="POST">
            <div>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="e.g. admin" required autofocus>
            </div>
            
             <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            
            <button type="submit">Login to Dashboard</button>
            <div class="text-center mt-6 pt-4 border-t border-slate-100">
    <p class="text-sm text-slate-500">
        <span style="color: #f4f1ff; font-family:Arial, sans-serif;">New to the system?</span>
        <a href="register.php" class="text-blue-600 font-semibold hover:text-blue-800 transition-colors">
            Create an account
        </a>
    </p>
        </form>
    </div>
</body>
</html>