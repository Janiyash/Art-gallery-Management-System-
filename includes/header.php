<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/db.php";

// FIX: For new database users table: id instead of user_id
$userLoggedIn = isset($_SESSION['id']);
$isAdmin = $userLoggedIn && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Artify Gallery</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
  body { font-family: "Poppins", sans-serif; }
  html { scroll-behavior: smooth; }
  .nav-link:hover { color: #dc2626; }
</style>
</head>

<body class="bg-white">

<header class="w-full sticky top-0 z-50 bg-white/80 backdrop-blur border-b border-red-200 shadow-sm">
  <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">

    <!-- LOGO -->
    <a href="index.php" class="flex items-center gap-3">
      <img src="assets/logo.jpg" class="w-10 h-10 rounded shadow border border-red-200">
      <h1 class="text-xl font-semibold text-red-700">Artify Gallery</h1>
    </a>

    <!-- NAVIGATION -->
    <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
      <a href="index.php#home" class="nav-link text-gray-700">Home</a>
      <a href="index.php#about" class="nav-link text-gray-700">About</a>
      <a href="gallery.php" class="nav-link text-gray-700">Gallery</a>
      <a href="index.php#contact" class="nav-link text-gray-700">Contact</a>
    </nav>

    <!-- RIGHT SIDE BUTTONS -->
    <div class="hidden md:flex items-center gap-4">

      <?php if ($userLoggedIn): ?>

          <?php if ($isAdmin): ?>
            <a href="admin/dashboard.php"
               class="px-4 py-1 rounded-full bg-red-600 text-white hover:bg-red-700 shadow">
              Admin Panel
            </a>
          <?php else: ?>
            <a href="my_purchases.php"
               class="px-4 py-1 rounded-full border border-red-500 text-red-600 hover:bg-red-50">
              My Purchases
            </a>
          <?php endif; ?>

          <a href="logout.php"
             class="px-4 py-1 rounded-full bg-gray-800 text-white hover:bg-gray-900">
             Logout
          </a>

      <?php else: ?>

          <a href="login.php"
             class="px-4 py-1 rounded-full border border-gray-300 hover:border-red-500 hover:text-red-600">
             Login
          </a>

          <a href="register.php"
             class="px-4 py-1 rounded-full bg-red-600 text-white hover:bg-red-700 shadow">
             Register
          </a>

      <?php endif; ?>

    </div>

  </div>
</header>


<!-- SMOOTH SCROLL FOR HASH LINKS WHEN COMING FROM ANOTHER PAGE -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    if (window.location.hash) {
        const target = document.querySelector(window.location.hash);
        if (target) {
            setTimeout(() => {
                target.scrollIntoView({ behavior: "smooth" });
            }, 200);
        }
    }
});

// Smooth scroll for internal page links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener("click", function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
            target.scrollIntoView({ behavior: "smooth" });
        }
    });
});
</script>
