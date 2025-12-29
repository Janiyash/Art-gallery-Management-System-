<?php
require_once "../includes/db.php";
require_once "check_admin.php";

// Handle add artwork
$message = "";

if (isset($_POST['add_artwork'])) {
    $title   = mysqli_real_escape_string($conn, $_POST['title']);
    $artist  = mysqli_real_escape_string($conn, $_POST['artist']);
    $cat_id  = (int)$_POST['category_id'];
    $price   = (float)$_POST['price'];
    $desc    = mysqli_real_escape_string($conn, $_POST['description']);

    $image_name = "";

    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $target = "../uploads/" . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $sql = "INSERT INTO artworks (title, artist, category_id, price, image, description)
            VALUES ('$title','$artist',$cat_id,$price,'$image_name','$desc')";

    if (mysqli_query($conn, $sql)) {
        $message = "Artwork added successfully.";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Fetch existing artworks
$arts = mysqli_query($conn, "
  SELECT a.*, c.name AS category
  FROM artworks a
  LEFT JOIN categories c ON a.category_id = c.category_id
  ORDER BY a.created_at DESC
");

// Fetch categories for dropdown
$cats = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Artworks | Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-red-50 min-h-screen">
  <div class="max-w-6xl mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">Manage Artworks</h1>
      <a href="logout.php" class="text-sm text-red-600">Logout</a>
    </div>

    <?php if ($message): ?>
      <p class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-2"><?= $message ?></p>
    <?php endif; ?>

    <!-- Add Artwork Form -->
    <div class="bg-white border border-red-200 rounded-2xl p-6 shadow-sm mb-8">
      <h2 class="text-lg font-semibold mb-4 text-gray-900">Add New Artwork</h2>

      <form method="POST" enctype="multipart/form-data" class="grid md:grid-cols-2 gap-4">

        <input type="text" name="title" placeholder="Title" required
               class="px-4 py-2 border border-red-200 rounded-lg">

        <input type="text" name="artist" placeholder="Artist" required
               class="px-4 py-2 border border-red-200 rounded-lg">

        <select name="category_id" required
                class="px-4 py-2 border border-red-200 rounded-lg">
          <option value="">Select Category</option>
          <?php while ($c = mysqli_fetch_assoc($cats)): ?>
            <option value="<?= $c['category_id']; ?>">
              <?= htmlspecialchars($c['name']); ?>
            </option>
          <?php endwhile; ?>
        </select>

        <input type="number" step="0.01" name="price" placeholder="Price (₹)" required
               class="px-4 py-2 border border-red-200 rounded-lg">

        <div class="md:col-span-2">
          <textarea name="description" rows="3" placeholder="Description"
                    class="w-full px-4 py-2 border border-red-200 rounded-lg"></textarea>
        </div>

        <div class="md:col-span-2">
          <input type="file" name="image" accept="image/*"
                 class="w-full text-sm">
        </div>

        <div class="md:col-span-2">
          <button name="add_artwork"
                  class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
            Add Artwork
          </button>
        </div>

      </form>
    </div>

    <!-- Artworks List -->
    <div class="bg-white border border-red-200 rounded-2xl p-6 shadow-sm">
      <h2 class="text-lg font-semibold mb-4 text-gray-900">All Artworks</h2>

      <div class="overflow-x-auto text-sm">
        <table class="min-w-full border-collapse">
          <thead>
            <tr class="border-b bg-red-50">
              <th class="px-3 py-2 text-left">ID</th>
              <th class="px-3 py-2 text-left">Title</th>
              <th class="px-3 py-2 text-left">Artist</th>
              <th class="px-3 py-2 text-left">Category</th>
              <th class="px-3 py-2 text-left">Price</th>
              <th class="px-3 py-2 text-left">Image</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($a = mysqli_fetch_assoc($arts)): ?>
              <tr class="border-b hover:bg-red-50/50">
                <td class="px-3 py-2"><?= $a['artwork_id']; ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($a['title']); ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($a['artist']); ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($a['category']); ?></td>
                <td class="px-3 py-2">₹<?= $a['price']; ?></td>
                <td class="px-3 py-2">
                  <?php if ($a['image']): ?>
                    <img src="../uploads/<?= $a['image']; ?>" class="w-12 h-12 object-cover rounded">
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    </div>

  </div>
</body>
</html>
