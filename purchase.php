                                          <?php
session_start();
require "includes/db.php";
require "includes/mail.php";

// User must login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Artwork ID required
if (!isset($_GET['artwork_id'])) {
    header("Location: index.php");
    exit;
}

$artwork_id = (int)$_GET['artwork_id'];
$user_id    = (int)$_SESSION['id'];

// Get artwork
$art = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM artworks WHERE id = $artwork_id
"));

if (!$art) {
    header("Location: index.php");
    exit;
}

// Form Submitted
if (isset($_POST['purchase'])) {

    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $quantity = (int)$_POST['quantity'];

    $total_price = $quantity * $art['price'];

    // 1. Insert into orders table
    mysqli_query($conn, "
        INSERT INTO orders (user_id, total_amount, status, created_at)
        VALUES ($user_id, $total_price, 'confirmed', NOW())
    ");

    $order_id = mysqli_insert_id($conn);

    // 2. Insert into order_items
    mysqli_query($conn, "
        INSERT INTO order_items (order_id, artwork_id, quantity, price)
        VALUES ($order_id, $artwork_id, $quantity, {$art['price']})
    ");

    // 3. Send Email Notification
    sendOrderMail($email, $art['title'], $total_price, $order_id);

    // 4. Trigger success popup
    $_SESSION['purchase_success'] = $order_id;

    header("Location: purchase.php?artwork_id=$artwork_id");
    exit;
}

include "includes/header.php";
?>


<!-- SUCCESS POPUP -->
<?php if (isset($_SESSION['purchase_success'])): ?>
<div id="successPopup"
     class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-gradient-to-br from-red-600 to-white text-red-800 rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl animate-popup">

        <!-- Tick Circle -->
        <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-600" fill="none" 
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h2 class="text-2xl font-semibold mb-1">Purchase Successful!</h2>

        <p class="text-sm text-gray-900 mb-5">
            Your order has been placed successfully.<br>
            <strong>Order ID:</strong> <?= $_SESSION['purchase_success']; ?>
        </p>

        <button onclick="document.getElementById('successPopup').remove();"
                class="bg-red-600 text-white px-6 py-2 rounded-full hover:bg-red-700 transition">
            Close
        </button>

    </div>
</div>

<style>
@keyframes popup {
    from { transform: scale(0.85); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
.animate-popup {
    animation: popup 0.3s ease-out;
}
</style>

<?php unset($_SESSION['purchase_success']); endif; ?>




<!-- PURCHASE PAGE -->
<div class="min-h-screen bg-white py-16 px-4 flex items-center justify-center">

    <div class="max-w-4xl w-full bg-white border border-red-100 rounded-2xl shadow-lg overflow-hidden grid md:grid-cols-2">

        <!-- Artwork Section -->
        <div class="bg-red-50 p-6 border-r border-red-100">

            <img src="uploads/<?= $art['image']; ?>" 
                 class="rounded-xl shadow mb-4">

            <h2 class="text-xl font-semibold text-red-700">
                <?= htmlspecialchars($art['title']); ?>
            </h2>

            <p class="text-sm text-gray-600 mt-1">
                Artist: <?= htmlspecialchars($art['artist']); ?>
            </p>

            <p class="mt-4 text-lg font-bold text-red-700">
                Price: ₹<?= $art['price']; ?> / piece
            </p>

        </div>

        <!-- Purchase Form -->
        <div class="p-6">

            <h2 class="text-2xl font-semibold text-red-700 mb-1">Purchase Artwork</h2>
            <p class="text-gray-600 mb-4 text-sm">Fill your details to confirm your purchase.</p>

            <form method="POST" class="space-y-4">

                <div>
                    <label class="text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" required
                           class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-red-500 focus:border-red-500"
                           value="<?= $_SESSION['name'] ?? '' ?>">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" required
                           class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-red-500 focus:border-red-500"
                           value="<?= $_SESSION['email'] ?? '' ?>">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" name="quantity" min="1" value="1" required
                           class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-red-500 focus:border-red-500">
                </div>

                <button name="purchase"
                        class="w-full bg-red-600 text-white py-3 rounded-full text-sm font-medium hover:bg-red-700 transition">
                    Confirm Purchase
                </button>

            </form>

        </div>

    </div>

</div>
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































































