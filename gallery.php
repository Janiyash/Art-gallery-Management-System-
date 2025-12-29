<?php
session_start();
require_once "includes/db.php";
include "includes/header.php";

// Get all categories
$cat_query = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");

// Selected category (if any)
$selected_cat = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Build WHERE condition
$where = "";
if ($selected_cat > 0) {
    $where = "WHERE a.category_id = $selected_cat";
}

// Fetch artworks
$arts = mysqli_query($conn, "
    SELECT a.*, c.name AS category_name
    FROM artworks a
    LEFT JOIN categories c ON a.id = c.id
    $where
    ORDER BY a.created_at DESC
");
?>

<!-- GALLERY HERO / TITLE -->
<section class="bg-gradient-to-b from-red-50 via-white to-red-50 py-10">
  <div class="max-w-6xl mx-auto px-4">
    <h1 class="text-3xl font-bold text-center text-gray-900 mb-2">Gallery</h1>
    <p class="text-center text-gray-600 mb-8">
      Browse our curated collection of paintings, illustrations, and digital artworks.
    </p>

    <!-- CATEGORY FILTER BUTTONS -->
    <div class="flex flex-wrap justify-center gap-3 mb-8">
      <!-- All button -->
      <a href="gallery.php"
         class="px-4 py-2 rounded-full border
                <?= $selected_cat === 0 ? 'bg-red-600 text-white border-red-600' : 'border-red-200 text-red-700 hover:bg-red-50'; ?>">
        All
      </a>

      <?php while ($cat = mysqli_fetch_assoc($cat_query)): ?>
        <a href="gallery.php?category=<?= $cat['id']; ?>"
           class="px-4 py-2 rounded-full border
                  <?= $selected_cat === (int)$cat['id']
                       ? 'bg-red-600 text-white border-red-600'
                       : 'border-red-200 text-red-700 hover:bg-red-50'; ?>">
          <?= htmlspecialchars($cat['name']); ?>
        </a>
      <?php endwhile; ?>
    </div>

    <!-- ARTWORK GRID -->
    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8">
      <?php if (mysqli_num_rows($arts) === 0): ?>
        <p class="col-span-full text-center text-gray-500 text-sm">
          No artworks found in this category.
        </p>
      <?php else: ?>
        <?php while ($art = mysqli_fetch_assoc($arts)): ?>
          <div class="bg-white border border-red-100 rounded-2xl shadow-sm hover:shadow-lg transition hover:-translate-y-1">

            <img src="uploads/<?= htmlspecialchars($art['image']); ?>"
                 alt="<?= htmlspecialchars($art['title']); ?>"
                 class="w-full h-48 object-cover rounded-t-2xl">

            <div class="p-5 space-y-2">
              <p class="text-xs text-red-600 font-medium">
                <?= htmlspecialchars($art['category_name'] ?? 'Art'); ?>
              </p>

              <h3 class="text-lg font-semibold text-gray-900">
                <?= htmlspecialchars($art['title']); ?>
              </h3>

              <p class="text-sm text-gray-600">
                By <?= htmlspecialchars($art['artist']); ?>
              </p>

              <p class="text-lg font-bold text-red-600">
                ₹<?= (int)$art['price']; ?>
              </p>

              <a href="purchase.php?artwork_id=<?= $art['id']; ?>"
                 class="block w-full mt-2 px-4 py-2 text-center text-sm
                        bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold
                        rounded-full shadow-md hover:from-red-700 hover:to-red-600 transition">
                  Purchase Now
              </a>

            </div>
          </div>
        <?php endwhile; ?>
      <?php endif; ?>
    </div>

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
