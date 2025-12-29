<?php
require "../includes/db.php";
require "check_admin.php";

// Stats
$total_users     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users"))['c'];
$total_artworks  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM artworks"))['c'];
$total_orders    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM orders"))['c'];
$total_earnings  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL(SUM(total_amount),0) AS s FROM orders"))['s'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | Artify</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
.active-link {
    background-color: rgba(255, 255, 255, 0.25) !important;
    color: #fff !important;
    box-shadow: inset 0 0 6px rgba(255,255,255,0.4);
    font-weight: 600;
}

.sidebar-link:hover {
    background-color: rgba(255, 255, 255, 0.18);
    transition: 0.3s;
}
</style>

</head>

<body class="bg-red-50 min-h-screen flex">

<aside class="w-64 bg-gradient-to-b from-red-700 to-red-900 text-white 
           min-h-screen shadow-xl flex flex-col fixed">

  <!-- LOGO -->
  <div class="flex items-center gap-3 px-6 py-5 border-b border-red-500/40">
    <img src="../assets/logo.jpg" class="w-10 h-10 rounded shadow">
    <h2 class="text-lg font-semibold tracking-wide">Artify Admin</h2>
  </div>

  <!-- MENU -->
  <nav class="flex-1 px-4 py-6 space-y-1">

    <a href="dashboard.php"
       class="sidebar-link block px-4 py-2 rounded-lg 
              <?php if(basename($_SERVER['PHP_SELF'])=='dashboard.php') echo 'active-link'; ?>">
      📊 Dashboard
    </a>

    <a href="manage_artworks.php"
       class="sidebar-link block px-4 py-2 rounded-lg 
              <?php if(basename($_SERVER['PHP_SELF'])=='manage_artworks.php') echo 'active-link'; ?>">
      🎨 Manage Artworks
    </a>

    <a href="manage_categories.php"
       class="sidebar-link block px-4 py-2 rounded-lg 
              <?php if(basename($_SERVER['PHP_SELF'])=='manage_categories.php') echo 'active-link'; ?>">
      🗂 Manage Categories
    </a>

    <a href="manage_orders.php"
       class="sidebar-link block px-4 py-2 rounded-lg 
              <?php if(basename($_SERVER['PHP_SELF'])=='manage_orders.php') echo 'active-link'; ?>">
      🧾 Manage Orders
    </a>

    <a href="manage_users.php"
       class="sidebar-link block px-4 py-2 rounded-lg 
              <?php if(basename($_SERVER['PHP_SELF'])=='manage_users.php') echo 'active-link'; ?>">
      👤 Manage Users
    </a>

  </nav>

  <!-- BOTTOM BUTTON -->
  <div class="px-5 py-5 border-t border-red-500/40">
    <a href="../index.php"
       class="block w-full text-center 
              py-3 
              bg-gradient-to-r from-white/90 to-white/70
              text-red-700
              font-semibold 
              text-sm
              tracking-wide
              rounded-xl
              shadow-md
              hover:shadow-lg
              hover:from-white
              hover:to-white/90
              transition-all">
        ← Back to Site
    </a>
  </div>
</aside>


<!-- ========== RIGHT MAIN CONTENT ========== -->
<main class="flex-1 p-8 ml-64">

  <h1 class="text-3xl font-semibold text-red-800 mb-8">Dashboard Overview</h1>

  <div class="grid md:grid-cols-4 gap-6 mb-10">

    <div class="bg-white rounded-xl shadow border border-red-100 p-5 hover:shadow-lg transition">
      <p class="text-xs text-gray-500">Total Users</p>
      <p class="text-3xl font-semibold text-red-700"><?= $total_users ?></p>
    </div>

    <div class="bg-white rounded-xl shadow border border-red-100 p-5 hover:shadow-lg transition">
      <p class="text-xs text-gray-500">Total Artworks</p>
      <p class="text-3xl font-semibold text-red-700"><?= $total_artworks ?></p>
    </div>

    <div class="bg-white rounded-xl shadow border border-red-100 p-5 hover:shadow-lg transition">
      <p class="text-xs text-gray-500">Total Orders</p>
      <p class="text-3xl font-semibold text-red-700"><?= $total_orders ?></p>
    </div>

    <div class="bg-white rounded-xl shadow border border-red-100 p-5 hover:shadow-lg transition">
      <p class="text-xs text-gray-500">Total Earnings</p>
      <p class="text-3xl font-semibold text-red-700">₹<?= $total_earnings ?></p>
    </div>

  </div>

  <h2 class="text-xl font-semibold text-red-800 mb-4">Quick Actions</h2>

  <div class="grid md:grid-cols-3 gap-6">

    <a href="manage_artworks.php"
       class="bg-white p-5 border border-red-100 rounded-xl shadow hover:shadow-lg transition">
      <h3 class="font-semibold text-red-700 mb-1">🎨 Manage Artworks</h3>
      <p class="text-sm text-gray-600">Add, edit or delete artworks</p>
    </a>

    <a href="manage_orders.php"
       class="bg-white p-5 border border-red-100 rounded-xl shadow hover:shadow-lg transition">
      <h3 class="font-semibold text-red-700 mb-1">🧾 Manage Orders</h3>
      <p class="text-sm text-gray-600">View recent purchases</p>
    </a>

    <a href="manage_users.php"
       class="bg-white p-5 border border-red-100 rounded-xl shadow hover:shadow-lg transition">
      <h3 class="font-semibold text-red-700 mb-1">👤 Manage Users</h3>
      <p class="text-sm text-gray-600">View registered users</p>
    </a>

  </div>

</main>

</body>
</html>
