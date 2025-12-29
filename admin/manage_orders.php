<?php
session_start();
require_once "../includes/db.php";
require_once "check_admin.php";

$success_msg = "";
$error_msg = "";

// UPDATE ORDER STATUS
if (isset($_POST['update_status'])) {

    $order_id   = (int)$_POST['order_id'];
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);

    // VALID VALUES ONLY
    $valid = ['pending','paid','shipped','cancelled'];
    if (!in_array($new_status, $valid)) {
        $error_msg = "Invalid status!";
    } else {
        $update = mysqli_query($conn, "
            UPDATE orders 
            SET status='$new_status'
            WHERE id=$order_id
        ");

        if ($update) {
            $success_msg = "Status updated successfully!";
        } else {
            $error_msg = "Failed to update status!";
        }
    }
}

// FETCH ALL ORDERS
$orders = mysqli_query($conn, "
    SELECT o.*, u.name AS customer, u.email
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders | Artify Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>

<style>
.active-link {
    background-color: rgba(255,255,255,0.25)!important;
    color: #fff!important;
    font-weight: 600;
}
.sidebar-link:hover {
    background-color: rgba(255,255,255,0.18);
}
</style>

</head>

<body class="bg-red-50 min-h-screen">

<div class="flex">

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

    <?php if ($success_msg): ?>
        <div class="p-3 mb-4 bg-green-100 text-green-700 border border-green-400 rounded">
            <?= $success_msg ?>
        </div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
        <div class="p-3 mb-4 bg-red-100 text-red-700 border border-red-400 rounded">
            <?= $error_msg ?>
        </div>
    <?php endif; ?>

    <h1 class="text-2xl font-semibold text-red-700 mb-4">Manage Orders</h1>

    <div class="bg-white p-6 shadow rounded-xl border border-red-100">

        <table class="w-full border-collapse">
    <thead class="bg-red-100">
        <tr>
            <th class="p-2 border">Order ID</th>
            <th class="p-2 border">Customer</th>
            <th class="p-2 border">Email</th>
            <th class="p-2 border">Amount</th>
            <th class="p-2 border">Status</th>
        </tr>
    </thead>

    <tbody>
    <?php while ($o = mysqli_fetch_assoc($orders)): ?>
        <tr class="border">

            <td class="p-2 border"><?= $o['id'] ?></td>
            <td class="p-2 border"><?= $o['customer'] ?></td>
            <td class="p-2 border text-sm"><?= $o['email'] ?></td>
            <td class="p-2 border font-semibold">₹<?= $o['total_amount'] ?></td>

            <td class="p-2 border">
                <form method="POST" class="flex gap-2">

                    <select name="status" class="border p-1 rounded text-sm">

                        <option value="pending"   <?= ($o['status']=="pending" ? "selected" : "") ?>>pending</option>
                        <option value="paid"      <?= ($o['status']=="paid" ? "selected" : "") ?>>paid</option>
                        <option value="shipped"   <?= ($o['status']=="shipped" ? "selected" : "") ?>>shipped</option>
                        <option value="cancelled" <?= ($o['status']=="cancelled" ? "selected" : "") ?>>cancelled</option>

                    </select>

                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">

                    <button name="update_status" 
                        class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">
                        Update
                    </button>
                </form>
            </td>

        </tr>
    <?php endwhile; ?>
    </tbody>
</table>


    </div>
</div>

</body>
</html>
