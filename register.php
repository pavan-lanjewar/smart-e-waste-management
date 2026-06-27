<?php
require 'db.php';
session_start();

// show errors while debugging (optional, you can remove later)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? '';

    // basic validation
    if ($name === '' || $email === '' || $password === '' || $role === '') {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {

        // our DB enum has: 'user','collector','admin'
        // if user selects recycler, map it to collector (or change enum in DB if you want recycler)
        if ($role === 'recycler') {
            $role = 'collector';
        }

        // safety: allow only known roles
        $allowed_roles = ['user', 'collector', 'admin'];
        if (!in_array($role, $allowed_roles, true)) {
            $error = 'Invalid role selected.';
        } else {
            try {
                // check if email already exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = 'This email is already registered.';
                } else {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);

                    // phone & address left NULL here; you can add fields later
                    $stmt = $pdo->prepare(
                        "INSERT INTO users (name, email, password_hash, phone, address, role)
                         VALUES (?, ?, ?, NULL, NULL, ?)"
                    );
                    $stmt->execute([$name, $email, $password_hash, $role]);

                    $success = 'Registration successful! You can now log in.';
                }
            } catch (Exception $e) {
                $error = 'Error: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | Smart E-Waste Management System</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="center-login">

  <div class="login-card">
    <div class="login-header">
      <img src="img1.jpg" alt="e-waste logo">
      <h2>Create Account</h2>
      <p>Join the Smart E-Waste community ♻️</p>
    </div>

    <?php if (!empty($error)): ?>
      <p style="color:red; text-align:center; margin-bottom:10px;">
        <?php echo htmlspecialchars($error); ?>
      </p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <p style="color:green; text-align:center; margin-bottom:10px;">
        <?php echo htmlspecialchars($success); ?>
      </p>
    <?php endif; ?>

    <form action="register.php" method="post" class="login-form">
      <label>Full Name</label>
      <input type="text" name="name" placeholder="Enter your name" required>

      <label>Email Address</label>
      <input type="email" name="email" placeholder="Enter your email" required>

      <label>Password</label>
      <input type="password" name="password" placeholder="Create a password" required>

      <label>Role</label>
      <select name="role" required>
        <option value="" disabled selected>Select your role</option>
        <option value="user">User</option>
        <option value="collector">Collector</option>
        <option value="recycler">Recycler</option>
        <option value="admin">Admin</option>
      </select>

      <button type="submit">Register</button>
    </form>

    <p class="register-link">Already have an account? <a href="login.php">Login here</a></p>
  </div>

</body>
</html>
