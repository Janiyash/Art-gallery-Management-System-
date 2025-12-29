<?php
require "../includes/db.php";
require "check_admin.php";

$message = "";

// Add category
if (isset($_POST['add_category'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$name')");
    $message = "Category added.";
}

// Delete category
if (isset($_GET['delete'])) {
    $cid = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM categories WHERE id=$cid");
    $message = "Category deleted.";
}

$cats = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Categories | Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-red-50 min-h-screen">
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
<div class="flex-1 p-6 ml-64">

    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-semibold text-red-700">Manage Categories</h1>
      <a href="dashboard.php" class="text-sm text-gray-700 hover:text-red-600">← Back to Dashboard</a>
    </div>

    <?php if ($message): ?>
      <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded">
        <?= htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <div class="bg-white border border-red-100 rounded-2xl shadow p-5 mb-6">
      <h2 class="text-lg font-semibold text-red-700 mb-3">Add Category</h2>
      <form method="POST" class="flex gap-3">
        <input type="text" name="name" required placeholder="Category name"
               class="flex-1 px-3 py-2 border rounded-lg">
        <button name="add_category"
                class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
          Add
        </button>
      </form>
    </div>

    <div class="bg-white border border-red-100 rounded-2xl shadow p-5">
      <h2 class="text-lg font-semibold text-red-700 mb-3">All Categories</h2>

      <ul class="space-y-2 text-sm">
        <?php while ($c = mysqli_fetch_assoc($cats)): ?>
          <li class="flex justify-between items-center border-b last:border-b-0 pb-1">
            <span><?= htmlspecialchars($c['name']); ?></span>
            <a href="manage_categories.php?delete=<?= $c['id']; ?>"
               onclick="return confirm('Delete this category?');"
               class="text-xs px-3 py-1 border rounded-full border-red-500 text-red-600 hover:bg-red-50">
              Delete
            </a>
          </li>
        <?php endwhile; ?>
      </ul>

    </div>

  </div>
</body>
</html>

