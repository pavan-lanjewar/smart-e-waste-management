<?php
require 'db.php';
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `feedbacks` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `name` varchar(120) NOT NULL,
      `email` varchar(255) NOT NULL,
      `rating` enum('Excellent','Good','Average','Poor') NOT NULL,
      `comments` text NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
    echo "Success.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
