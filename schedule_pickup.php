<?php
require 'db.php';
session_start();

// Only logged-in users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Fetch user's pending items
$stmt = $pdo->prepare(
    "SELECT * FROM ewaste_items 
     WHERE user_id = ? AND status = 'pending' 
     ORDER BY created_at DESC"
);
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup_date    = $_POST['pickup_date'] ?? '';
    $pickup_time    = $_POST['pickup_time'] ?? '';
    $pickup_address = trim($_POST['pickup_address'] ?? '');
    $notes          = trim($_POST['notes'] ?? '');
    $selected       = $_POST['items'] ?? [];

    if (empty($selected)) {
        $error = 'Please select at least one item for pickup.';
    } elseif ($pickup_date === '') {
        $error = 'Please select a pickup date.';
    } elseif ($pickup_address === '') {
        $error = 'Pickup address is required.';
    } else {
        try {
            $pdo->beginTransaction();

            // Insert pickup record
            $stmtPickup = $pdo->prepare(
                "INSERT INTO pickups 
                 (user_id, pickup_date, pickup_time, pickup_address, notes)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmtPickup->execute([
                $user_id,
                $pickup_date,
                $pickup_time ?: null,
                $pickup_address,
                $notes
            ]);

            $pickup_id = $pdo->lastInsertId();

            // Link items to this pickup
            $stmtLink = $pdo->prepare(
                "INSERT INTO pickup_items (pickup_id, ewaste_item_id, quantity)
                 VALUES (?, ?, ?)"
            );
            $stmtUpdateItem = $pdo->prepare(
                "UPDATE ewaste_items SET status = 'scheduled' WHERE id = ?"
            );

            foreach ($selected as $item_id) {
                $item_id = (int)$item_id;
                $stmtLink->execute([$pickup_id, $item_id, 1]); // quantity fixed as 1 for now
                $stmtUpdateItem->execute([$item_id]);
            }

            // First tracking entry
            $stmtTrack = $pdo->prepare(
                "INSERT INTO pickup_tracking (pickup_id, status, notes)
                 VALUES (?, 'requested', ?)"
            );
            $stmtTrack->execute([$pickup_id, 'Pickup requested by user']);

            $pdo->commit();

            $success = 'Pickup scheduled successfully!';
            // refresh items list (pending will be fewer now)
            $stmt = $pdo->prepare(
                "SELECT * FROM ewaste_items 
                 WHERE user_id = ? AND status = 'pending' 
                 ORDER BY created_at DESC"
            );
            $stmt->execute([$user_id]);
            $items = $stmt->fetchAll();

        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Schedule Pickup | Smart E-Waste Management</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: #edf2f7;
      color: #2d3748;
      margin: 0;
      padding: 0;
    }

    header {
      background: linear-gradient(90deg, #2c7a7b, #319795);
      color: white;
      padding: 15px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    header h1 {
      font-size: 22px;
      letter-spacing: 1px;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin-left: 20px;
      font-weight: 500;
      transition: 0.3s;
    }

    nav a:hover {
      text-decoration: underline;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      background: white;
      padding: 25px 35px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #2c7a7b;
      margin-bottom: 20px;
    }

    .msg {
      text-align: center;
      margin-bottom: 15px;
      font-weight: 500;
    }

    .msg.error { color: #e53e3e; }
    .msg.success { color: #38a169; }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th, td {
      padding: 10px;
      border-bottom: 1px solid #e2e8f0;
      text-align: left;
    }

    th {
      background-color: #f7fafc;
      font-weight: 600;
    }

    label {
      font-weight: 500;
    }

    input[type="date"],
    input[type="time"],
    textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #cbd5e0;
      border-radius: 8px;
      font-size: 14px;
    }

    textarea {
      resize: vertical;
    }

    button {
      background-color: #2c7a7b;
      color: white;
      padding: 10px 22px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 15px;
      transition: 0.3s;
    }

    button:hover {
      background-color: #285e61;
    }

    .back-link {
      display: inline-block;
      margin-top: 10px;
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
      <a href="schedule_pickup.php">Schedule Pickup</a>
      <a href="track_status.php">Track Status</a>
      <a href="feedback.php">Feedback</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <div class="container">
    <h2>Schedule Pickup</h2>

    <?php if (!empty($error)): ?>
      <div class="msg error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <div class="msg success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if (count($items) === 0): ?>
      <p class="msg">You have no pending items. Please add items first.</p>
      <p style="text-align:center;">
        <a href="add_ewaste.php" class="back-link">➕ Add E-Waste Item</a>
      </p>
    <?php else: ?>

    <form action="schedule_pickup.php" method="post">
      <h3>Select Items for Pickup</h3>
      <table>
        <tr>
          <th>Select</th>
          <th>Category</th>
          <th>Brand</th>
          <th>Model / Name</th>
          <th>Condition</th>
          <th>Quantity</th>
        </tr>
        <?php foreach ($items as $it): ?>
          <tr>
            <td>
              <input type="checkbox" name="items[]" value="<?php echo (int)$it['id']; ?>">
            </td>
            <td><?php echo htmlspecialchars($it['item_type']); ?></td>
            <td><?php echo htmlspecialchars($it['brand']); ?></td>
            <td><?php echo htmlspecialchars($it['model']); ?></td>
            <td><?php echo htmlspecialchars($it['item_condition']); ?></td>
            <td><?php echo (int)$it['quantity']; ?></td>
          </tr>
        <?php endforeach; ?>
      </table>

      <h3>Pickup Details</h3>
      <table>
        <tr>
          <td style="width: 180px;"><label for="pickup_date">Pickup Date:</label></td>
          <td><input type="date" id="pickup_date" name="pickup_date" required></td>
        </tr>
        <tr>
          <td><label for="pickup_time">Pickup Time (optional):</label></td>
          <td><input type="time" id="pickup_time" name="pickup_time"></td>
        </tr>
        <tr>
          <td><label for="pickup_address">Pickup Address:</label></td>
          <td><textarea id="pickup_address" name="pickup_address" rows="3" placeholder="Enter full address" required></textarea></td>
        </tr>
        <tr>
          <td><label for="notes">Notes (optional):</label></td>
          <td><textarea id="notes" name="notes" rows="2" placeholder="Any special instructions"></textarea></td>
        </tr>
        <tr>
          <td></td>
          <td><button type="submit">Confirm Pickup Request</button></td>
        </tr>
      </table>
    </form>

    <?php endif; ?>

    <a href="user.php" class="back-link">⬅ Back to Dashboard</a>
  </div>

</body>
</html>
