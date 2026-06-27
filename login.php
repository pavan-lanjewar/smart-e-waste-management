<?php
require 'db.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = "Email and password are required.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, password_hash, role, name FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {

                // store user session data
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role']    = $user['role'];
                $_SESSION['name']    = $user['name'];

                // redirect to dashboard/homepage
                header("Location: user.php");
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Smart E-Waste Management System</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="center-login">

  <div class="login-card">
    <div class="login-header">
      <img src="img1.jpg" alt="e-waste logo">
      <h2>Welcome Back</h2>
      <p>Login to continue ♻️</p>
    </div>

    <?php if (!empty($error)): ?>
      <p style="color:red; text-align:center; margin-bottom:10px;">
        <?php echo htmlspecialchars($error); ?>
      </p>
    <?php endif; ?>

    <form action="login.php" method="post" class="login-form">
      <label>Email Address</label>
      <input type="email" name="email" placeholder="Enter your email" required>

      <label>Password</label>
      <input type="password" name="password" placeholder="Enter your password" required>

      <button type="submit">Login</button>
    </form>

    <p class="register-link">Don't have an account? <a href="register.php">Create one</a></p>
  </div>

</body>
</html>
