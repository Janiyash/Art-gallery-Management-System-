<?php
session_start();
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['id'];

// CORRECT QUERY
$query = mysqli_query($conn, "
    SELECT 
        oi.quantity,
        oi.price AS item_price,
        
        a.id AS artwork_id,
        a.title,
        a.image,
        a.artist,
        a.price AS artwork_price,

        o.id AS order_id,
        o.total_amount,
        o.status,
        o.created_at

    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN artworks a ON oi.artwork_id = a.id
    WHERE o.user_id = '$user_id'
    ORDER BY o.created_at DESC
");

if (isset($_SESSION['purchase_success'])) {
    echo "
    <script>
        alert('Purchase Successful! Your Order ID: " . $_SESSION['purchase_success'] . "');
    </script>
    ";
    unset($_SESSION['purchase_success']);
}

include "includes/header.php";
?>

<section class="min-h-[80vh] px-4 py-14 bg-gradient-to-b from-red-50 to-white">

    <h2 class="text-3xl font-semibold text-center text-red-700 mb-10">
        My Purchases
    </h2>

    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-6">
        
        <?php if (mysqli_num_rows($query) == 0): ?>
            <p class="text-center text-gray-600 w-full col-span-2">
                You have not purchased any artworks yet.
            </p>
        <?php endif; ?>

        <?php while ($row = mysqli_fetch_assoc($query)): ?>
        <div class="bg-white border border-red-200 rounded-xl shadow p-4 hover:shadow-lg transition">
            
            <img src="uploads/<?= htmlspecialchars($row['image']); ?>" 
                 class="w-full h-48 object-cover rounded-md">

            <h3 class="text-lg font-semibold text-red-700 mt-3">
                <?= htmlspecialchars($row['title']); ?>
            </h3>

            <p class="text-gray-600 text-sm">
                Artist: <?= htmlspecialchars($row['artist']); ?>
            </p>

            <p class="text-red-600 font-bold mt-1">
                ₹<?= $row['item_price']; ?> 
                <span class="text-xs text-gray-500">(each)</span>
            </p>

            <p class="text-gray-700 text-sm mt-1">
                Quantity: <?= $row['quantity']; ?>
            </p>

            <p class="text-gray-500 text-sm mt-2">
                Order Date: <?= $row['created_at']; ?>
            </p>


        </div>
        <?php endwhile; ?>

    </div>
</section>
<script>
  // Disable right-click
  document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
  });

  // Disable special keys
  document.onkeydown = function(e) {
    if (e.key == "F12") return false;
    if (e.ctrlKey && e.shiftKey && e.key == "I") return false;
    if (e.ctrlKey && e.shiftKey && e.key == "J") return false;
    if (e.ctrlKey && e.key == "U") return false;
  };
</script>

<?php include "includes/footer.php"; ?>
