<?php
require "../includes/db.php";
require "check_admin.php";

// Delete user
if (isset($_GET['delete'])) {
    $uid = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE user_id=$uid AND role!='admin'");
}

// Change Role
if (isset($_GET['toggle_role'])) {
    $uid = (int)$_GET['toggle_role'];
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT role FROM users WHERE id=$uid"));

    if ($user) {
        $new_role = ($user['role'] == 'admin') ? 'user' : 'admin';
        mysqli_query($conn, "UPDATE users SET role='$new_role' WHERE id=$uid");
        header("Location: manage_users.php?success=Role updated successfully");
        exit;
    }
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users | Admin</title>
<script src="https://cdn.tailwindcss.com"></script>

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


<!-- RIGHT SIDE -->
<main class="flex-1 p-8 ml-64">

  <h1 class="text-2xl font-semibold text-red-700 mb-4">Manage Users</h1>

  <?php if (isset($_GET['success'])): ?>
    <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded-lg shadow">
        <?= htmlspecialchars($_GET['success']); ?>
    </div>
  <?php endif; ?>

  <div class="bg-white border border-red-100 rounded-2xl shadow p-5">
    <h2 class="text-lg font-semibold text-red-700 mb-3">All Users</h2>

    <div class="overflow-x-auto text-sm">
      <table class="min-w-full border-collapse">
        <thead>
          <tr class="bg-red-50 border-b">
            <th class="px-3 py-2 text-left">ID</th>
            <th class="px-3 py-2 text-left">Name</th>
            <th class="px-3 py-2 text-left">Email</th>
            <th class="px-3 py-2 text-left">Role</th>
            <th class="px-3 py-2 text-left">Actions</th>
          </tr>
        </thead>
<tbody>

<?php while ($u = mysqli_fetch_assoc($users)): ?>
<tr class="border">

    <td class="p-2 border"><?= $u['id'] ?></td>
    <td class="p-2 border"><?= htmlspecialchars($u['name']) ?></td>
<td class="p-2 border"><?= htmlspecialchars($u['email']) ?></td>
    <td class="p-2 border font-semibold"><?= $u['role'] ?></td>

    <td class="p-2 border flex gap-2">

        <!-- Toggle Role -->
        <a href="manage_users.php?toggle_role=<?= $u['id'] ?>"
           class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
            change Role
        </a>

        <!-- Delete (block admin delete) -->
        <?php if ($u['role'] != 'admin'): ?>
            <a href="manage_users.php?delete=<?= $u['id'] ?>"
               onclick="return confirm('Are you sure you want to delete this user?');"
               class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">
                Delete
            </a>
        <?php endif; ?>

    </td>

</tr>
<?php endwhile; ?>

</tbody>


      </table>
    </div>

  </div>

</main>

</body>
</html>
