<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard | Smart E-Waste Management System</title>
  <link rel="stylesheet" href="style.css">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: "Poppins", sans-serif;
    }

    body {
      background: #f0fdf4;
      color: #2d3748;
    }

    /* Navbar */
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

    /* Hero Section */
    .hero {
      background: url("/Smart_e-waste_management _system/img3.jpg") center/cover no-repeat;
      height: 60vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
    }

    .hero h2 {
      font-size: 40px;
      margin-bottom: 10px;
    }

    .hero p {
      font-size: 18px;
      width: 70%;
      text-align: center;
    }

    /* Columns / Cards Section */
    .features {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
      padding: 60px 80px;
    }

    .card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
      text-align: center;
      padding: 30px 20px;
      transition: 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .card img {
      width: 100px;
      height: 100px;
      margin-bottom: 15px;
    }

    .card h3 {
      color: #2c7a7b;
      margin-bottom: 10px;
      font-size: 20px;
    }

    .card p {
      font-size: 15px;
      color: #4a5568;
      margin-bottom: 15px;
    }

    .card button {
      padding: 10px 18px;
      background-color: #2c7a7b;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }

    .card button:hover {
      background-color: #285e61;
    }

    /* Footer */
    footer {
      background-color: #2c7a7b;
      color: white;
      text-align: center;
      padding: 15px;
      margin-top: 50px;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <header>
    <h1>Smart E-Waste Management</h1>
    <nav>
      <a href="#">Home</a>
      <a href="add_ewaste.php" class="back-link">Add E-waste</a>
      <a href="schedule_pickup.php" class="back-link">Schedule Pickup</a>
      <a href="track_status.php" class="back-link">Track</a>
      <a href="feedback.php" class="back-link">Feedback</a>

           <a href="logout.php">Logout</a>
    </nav>
  </header>

  <!-- Hero -->
  <section class="hero">
    <h2>Welcome to Your Dashboard ♻️</h2>
    <p>Manage your e-waste responsibly — schedule pickups, track recycling, and contribute to a cleaner environment.</p>
  </section>

  <!-- Features / Columns -->
  <section class="features">
    <div class="card">
      <img src="https://cdn-icons-png.flaticon.com/512/1684/1684375.png" alt="Add E-Waste">
      <h3>Add E-Waste</h3>
      <p>List the e-waste items like mobiles, laptops, and batteries for proper recycling.</p>
      <button onclick="window.location.href='add_ewaste.php'">Add Now</button>

    </div>

    <div class="card">
      <img src="https://cdn-icons-png.flaticon.com/512/854/854878.png" alt="Schedule Pickup">
      <h3>Schedule Pickup</h3>
      <p>Book a pickup at your convenience. Our collectors will come to your doorstep.</p>
      <button onclick="window.location.href='schedule_pickup.php'">Schedule</button>
   </div>

    <div class="card">
      <img src="https://cdn-icons-png.flaticon.com/512/1077/1077114.png" alt="Track Status">
      <h3>Track Status</h3>
      <p>Monitor the progress of your pickup from request to delivery in real time.</p>
      <button onclick="window.location.href='track_status.php'">Track</button>

    </div>

    <div class="card">
      <img src="https://cdn-icons-png.flaticon.com/512/3158/3158062.png" alt="Feedback">
      <h3>Feedback</h3>
      <p>Share your experience and help us improve the collection and recycling process.</p>
      <button onclick="window.location.href='feedback.php'">Give Feedback</button>

    </div>
  </section>
  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Smart E-Waste Management | Designed for a Greener Future 🌍</p>
  </footer>

</body>
</html>
