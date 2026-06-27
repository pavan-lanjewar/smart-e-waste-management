<?php
// logout.php — Smart E-Waste Management System

session_start();

// Remove all session variables
session_unset();

// Destroy the session
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Logout | Smart E-Waste Management</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, #2c7a7b, #319795);
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      text-align: center;
    }

    .logout-box {
      background: rgba(255, 255, 255, 0.15);
      padding: 40px 60px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    h1 {
      font-size: 32px;
      margin-bottom: 15px;
    }

    p {
      font-size: 18px;
      margin-bottom: 25px;
    }

    a {
      background: #f0fdf4;
      color: #2c7a7b;
      padding: 12px 25px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: 0.3s;
    }

    a:hover {
      background: #c6f6d5;
    }
  </style>
</head>
<body>

  <div class="logout-box">
    <h1>You have been logged out!</h1>
    <p>Thank you for using the Smart E-Waste Management System.</p>
    <a href="login.php">🔑 Login Again</a>
  </div>

  <!-- Optional: Auto redirect to login page after few seconds -->
  <script>
    setTimeout(() => {
      window.location.href = "login.php";
    }, 4000); // Redirect after 4 seconds
  </script>

</body>
</html>
