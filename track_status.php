<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$tracking_result = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup_id = trim($_POST['pickup_id'] ?? '');
    if ($pickup_id === '') {
        $error = "Please enter a Pickup ID.";
    } else {
        // fetch pickup details
        $stmt = $pdo->prepare("SELECT p.id as pickup_id, p.pickup_date, p.status as pickup_status, pt.status as tracking_status, pt.notes, pt.location 
                               FROM pickups p 
                               LEFT JOIN pickup_tracking pt ON p.id = pt.pickup_id 
                               WHERE p.id = ? AND p.user_id = ? 
                               ORDER BY pt.id DESC LIMIT 1");
        $stmt->execute([$pickup_id, $user_id]);
        $tracking_result = $stmt->fetch();
        if (!$tracking_result) {
            $error = "No pickup found with that ID or you don't have access to it.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Track Pickup Status | Smart E-Waste Management</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body { font-family: "Poppins", sans-serif; background: #f0fdf4; color: #2d3748; }
    header {
      background: linear-gradient(90deg, #2c7a7b, #319795);
      color: white;
      padding: 15px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    nav a { color: white; margin-left: 20px; text-decoration: none; }
    nav a:hover { text-decoration: underline; }

    .container {
      max-width: 800px;
      margin: 50px auto;
      background: white;
      padding: 30px 40px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    h2 { text-align: center; color: #2c7a7b; margin-bottom: 20px; }

    table { width: 100%; border-collapse: collapse; }
    td { padding: 12px; }

    input { width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 8px; }

    button {
      background-color: #2c7a7b; color: white; padding: 12px 25px; border: none;
      border-radius: 8px; cursor: pointer; transition: 0.3s;
    }
    button:hover { background-color: #285e61; }

    .status-box {
      margin-top: 30px;
      background: #e6fffa;
      border-left: 5px solid #2c7a7b;
      padding: 15px;
      border-radius: 8px;
    }
    .back-link {
      display: inline-block;
      margin-top: 15px;
      text-decoration: none;
      color: #2c7a7b;
      font-weight: 500;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<header>
  <h1>Smart E-Waste Management</h1>
  <nav>
    <a href="user.php">Dashboard</a>
    <a href="add_ewaste.php">Add E-Waste</a>
    <a href="schedule_pickup.php">Schedule Pickup</a>
    <a href="feedback.php">Feedback</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>

<div class="container">
  <h2>Track Pickup Status</h2>
  <form action="track_status.php" method="post">
    <table>
      <tr>
        <td><label for="pickup_id">Enter Pickup ID:</label></td>
        <td><input type="text" id="pickup_id" name="pickup_id" placeholder="e.g., 1" required></td>
      </tr>
      <tr>
        <td></td>
        <td><button type="submit">Track</button></td>
      </tr>
    </table>
  </form>

  <?php if (!empty($error)): ?>
      <p style="color:red; text-align:center; margin-top: 15px;"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <?php if ($tracking_result): ?>
  <div class="status-box">
    <strong>Pickup ID:</strong> <?php echo htmlspecialchars($tracking_result['pickup_id']); ?><br>
    <strong>Pickup Date:</strong> <?php echo htmlspecialchars($tracking_result['pickup_date']); ?><br>
    <strong>Status:</strong> <span style="text-transform: capitalize;"><?php echo htmlspecialchars($tracking_result['tracking_status'] ?? $tracking_result['pickup_status']); ?></span><br>
    <?php if (!empty($tracking_result['notes'])): ?>
        <strong>Notes:</strong> <span><?php echo htmlspecialchars($tracking_result['notes']); ?></span>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <a href="user.php" class="back-link">⬅ Back to Dashboard</a>
</div>

</body>
</html>
