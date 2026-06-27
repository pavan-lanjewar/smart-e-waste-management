<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Feedback | Smart E-Waste Management</title>
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

    input, textarea {
      width: 100%; padding: 10px; border: 1px solid #cbd5e0; border-radius: 8px;
    }

    button {
      background-color: #2c7a7b; color: white; padding: 12px 25px;
      border: none; border-radius: 8px; cursor: pointer; transition: 0.3s;
    }
    button:hover { background-color: #285e61; }

    .back-link {
      display: inline-block; margin-top: 15px; text-decoration: none; color: #2c7a7b;
    }
    .back-link:hover { text-decoration: underline; }
  </style>
</head>
<body>

<header>
  <h1>Smart E-Waste Management</h1>
  <nav>
    <a href="user.php">Dashboard</a>
    <a href="add_ewaste.php">Add E-Waste</a>
    <a href="schedule_pickup.php">Schedule Pickup</a>
    <a href="track_status.php">Track Status</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>

<div class="container">
  <h2>Feedback Form</h2>
  <form action="#" method="post">
    <table>
      <tr>
        <td><label for="name">Name:</label></td>
        <td><input type="text" id="name" name="name" required></td>
      </tr>
      <tr>
        <td><label for="email">Email:</label></td>
        <td><input type="email" id="email" name="email" required></td>
      </tr>
      <tr>
        <td><label for="rating">Rating:</label></td>
        <td>
          <select id="rating" name="rating" required>
            <option value="">-- Select Rating --</option>
            <option>Excellent</option>
            <option>Good</option>
            <option>Average</option>
            <option>Poor</option>
          </select>
        </td>
      </tr>
      <tr>
        <td><label for="comments">Comments:</label></td>
        <td><textarea id="comments" name="comments" rows="4" placeholder="Write your feedback here..."></textarea></td>
      </tr>
      <tr>
        <td></td>
        <td><button type="submit">Submit Feedback</button></td>
      </tr>
    </table>
  </form>
  <a href="user.php" class="back-link">⬅ Back to Dashboard</a>
</div>

</body>
</html>
