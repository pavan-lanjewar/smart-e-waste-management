<?php
// adjust path if this file is not inside a subfolder:
  require 'db.php';
  // if add_ewaste.php is in a subfolder (e.g. user/add_ewaste.php)
// require 'db.php';     // use this instead if add_ewaste.php is in project root

session_start();

// only logged-in users can access
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // adjust path if needed
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id     = $_SESSION['user_id'];
    $item_name   = trim($_POST['item_name'] ?? '');
    $brand       = trim($_POST['brand'] ?? '');
    $type        = trim($_POST['type'] ?? '');
    $quantity    = (int)($_POST['quantity'] ?? 0);
    $condition   = $_POST['condition'] ?? '';
    $description = trim($_POST['description'] ?? '');

    // basic validation
    if ($item_name === '' || $brand === '' || $type === '' || $quantity < 1 || $condition === '') {
        $error = 'All fields except image are required.';
    } else {
        // map form condition to DB enum: working / partly_working / not_working
        $map = [
            'Working'      => 'working',
            'Not Working'  => 'not_working',
            'Damaged'      => 'not_working',   // map Damaged as not_working
        ];
        $item_condition = $map[$condition] ?? 'not_working';

        // handle image upload (optional)
        $image_path = null;
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg','image/png','image/gif','image/webp'];
            $tmp  = $_FILES['image']['tmp_name'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $tmp);
            finfo_close($finfo);

            if (in_array($mime, $allowed_types, true)) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0755, true);
                }
                $newName = 'uploads/' . uniqid('ew_', true) . '.' . $ext;
                if (move_uploaded_file($tmp, $newName)) {
                    $image_path = $newName;
                }
            }
        }

        try {
            // item_type = category (type), model = item_name
            $stmt = $pdo->prepare(
                "INSERT INTO ewaste_items 
                 (user_id, item_type, brand, model, item_condition, quantity, description, image_path)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $user_id,
                $type,          // category: Mobile/Laptop/etc.
                $brand,
                $item_name,     // model/name
                $item_condition,
                $quantity,
                $description,
                $image_path
            ]);

            $success = 'E-waste item submitted successfully!';
        } catch (Exception $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add E-Waste | Smart E-Waste Management</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: #f0fdf4;
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
      max-width: 800px;
      margin: 50px auto;
      background: white;
      padding: 30px 40px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #2c7a7b;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    td {
      padding: 12px;
      vertical-align: middle;
    }

    label {
      font-weight: 500;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"],
    select,
    textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #cbd5e0;
      border-radius: 8px;
      font-size: 15px;
    }

    textarea {
      resize: vertical;
    }

    button {
      background-color: #2c7a7b;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      transition: 0.3s;
    }

    button:hover {
      background-color: #285e61;
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
      <a href="schedule_pickup.php">Schedule Pickup</a>
      <a href="track_status.php">Track Status</a>
      <a href="feedback.php">Feedback</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <div class="container">
    <h2>Add E-Waste Details</h2>

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

    <form action="add_ewaste.php" method="post" enctype="multipart/form-data">
      <table>
        <tr>
          <td><label for="item_name">Item Name:</label></td>
          <td><input type="text" id="item_name" name="item_name" placeholder="e.g., Laptop, Mobile, Battery" required></td>
        </tr>

        <tr>
          <td><label for="brand">Brand:</label></td>
          <td><input type="text" id="brand" name="brand" placeholder="e.g., Dell, Samsung, HP" required></td>
        </tr>

        <tr>
          <td><label for="type">Category:</label></td>
          <td>
            <select id="type" name="type" required>
              <option value="">-- Select Category --</option>
              <option value="Mobile">Mobile</option>
              <option value="Laptop">Laptop</option>
              <option value="Battery">Battery</option>
              <option value="Television">Television</option>
              <option value="Other">Other</option>
            </select>
          </td>
        </tr>

        <tr>
          <td><label for="quantity">Quantity:</label></td>
          <td><input type="number" id="quantity" name="quantity" min="1" placeholder="Enter quantity" required></td>
        </tr>

        <tr>
          <td><label for="condition">Condition:</label></td>
          <td>
            <select id="condition" name="condition" required>
              <option value="">-- Select Condition --</option>
              <option value="Working">Working</option>
              <option value="Not Working">Not Working</option>
              <option value="Damaged">Damaged</option>
            </select>
          </td>
        </tr>

        <tr>
          <td><label for="image">Upload Image:</label></td>
          <td><input type="file" id="image" name="image" accept="image/*"></td>
        </tr>

        <tr>
          <td><label for="description">Description:</label></td>
          <td><textarea id="description" name="description" rows="3" placeholder="Write any additional details..."></textarea></td>
        </tr>

        <tr>
          <td></td>
          <td><button type="submit">Submit E-Waste</button></td>
        </tr>
      </table>
    </form>

    <a href="user.php" class="back-link">⬅ Back to Dashboard</a>

  </div>

</body>
</html>
