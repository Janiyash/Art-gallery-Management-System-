<?php
require_once "../includes/db.php";
require_once "check_admin.php";

$message = "";

// Fetch categories BEFORE form use
$cats = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");

// Fetch all artworks BEFORE table
$arts = mysqli_query($conn, "
    SELECT a.*, c.name AS category_name
    FROM artworks a
    LEFT JOIN categories c ON a.category_id = c.id
    ORDER BY a.id DESC
");

// If Edit ID exists → fetch artwork
$edit_art = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_q = mysqli_query($conn, "SELECT * FROM artworks WHERE artwork_id = $id");
    if ($edit_q && mysqli_num_rows($edit_q) > 0) {
        $edit_art = mysqli_fetch_assoc($edit_q);
    }
}

// If Delete pressed
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM artworks WHERE id = $id");
    header("Location: manage_artworks.php?success=Artwork Deleted Successfully");
    exit;
}

// Handle Add / Update form
if (isset($_POST['save_artwork'])) {
    $title   = mysqli_real_escape_string($conn, $_POST['title']);
    $artist  = mysqli_real_escape_string($conn, $_POST['artist']);
    $cat_id  = (int)$_POST['category_id'];
    $price   = (float)$_POST['price'];
    $desc    = mysqli_real_escape_string($conn, $_POST['description']);
    $artwork_id = isset($_POST['artwork_id']) ? (int)$_POST['artwork_id'] : 0;

    // Upload folder
    $upload_dir = __DIR__ . "/../uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    // Handle image
    $image_name = $_POST['old_image'] ?? "";
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
    }

    if ($artwork_id > 0) {
        // UPDATE
        mysqli_query($conn, "
            UPDATE artworks SET
            title='$title', artist='$artist', category_id=$cat_id,
            price=$price, image='$image_name', description='$desc'
            WHERE artwork_id=$artwork_id
        ");
        header("Location: manage_artworks.php?success=Artwork Updated Successfully");
        exit;

    } else {
        // INSERT
        mysqli_query($conn, "
            INSERT INTO artworks (title, artist, category_id, price, image, description)
            VALUES ('$title', '$artist', $cat_id, $price, '$image_name', '$desc')
        ");
        header("Location: manage_artworks.php?success=Artwork Added Successfully");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Artworks | Admin</title>
<script src="https://cdn.tailwindcss.com"></script>


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




<!-- =============== MAIN CONTENT =============== -->
<div class="flex-1 p-6 ml-64">


<?php if (isset($_GET['success'])): ?>
<div id="successPopup"
     class="fixed top-5 right-5 bg-gradient-to-r from-red-600 to-red-400 text-white px-6 py-3 rounded-lg shadow-xl animate-slide">
    <?= htmlspecialchars($_GET['success']); ?>
</div>

<script>
    setTimeout(() => {
        document.getElementById("successPopup").style.display = "none";
    }, 2000);
</script>

<style>
@keyframes slide {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
.animate-slide { animation: slide 0.4s ease-out; }
</style>
<?php endif; ?>

<h1 class="text-2xl font-semibold text-red-700 mb-4">Manage Artworks</h1>

<!-- ADD / EDIT FORM -->
<div class="bg-white border border-red-100 rounded-2xl shadow p-5 mb-6">

  <h2 class="text-lg font-semibold text-red-700 mb-3">
      <?= $edit_art ? "Edit Artwork (ID: ".$edit_art['artwork_id'].")" : "Add New Artwork"; ?>
  </h2>

  <form method="POST" enctype="multipart/form-data" class="grid md:grid-cols-2 gap-4">
    
    <input type="hidden" name="artwork_id" value="<?= $edit_art['artwork_id'] ?? ''; ?>">
    <input type="hidden" name="old_image" value="<?= $edit_art['image'] ?? ''; ?>">

    <input name="title" required placeholder="Title"
           class="px-3 py-2 border rounded"
           value="<?= htmlspecialchars($edit_art['title'] ?? ''); ?>">

    <input name="artist" required placeholder="Artist"
           class="px-3 py-2 border rounded"
           value="<?= htmlspecialchars($edit_art['artist'] ?? ''); ?>">

    <select name="category_id" required class="px-3 py-2 border rounded">
      <option value="">Select Category</option>
      <?php mysqli_data_seek($cats, 0); while ($c = mysqli_fetch_assoc($cats)): ?>
        <option value="<?= $c['id']; ?>"
          <?= $edit_art && $edit_art['id']==$c['id'] ? 'selected' : ''; ?>>
          <?= htmlspecialchars($c['name']); ?>
        </option>
      <?php endwhile; ?>
    </select>

    <input type="number" name="price" min="0" step="0.01" required placeholder="Price"
           class="px-3 py-2 border rounded"
           value="<?= htmlspecialchars($edit_art['price'] ?? ''); ?>">

    <textarea name="description" rows="3" placeholder="Description"
              class="md:col-span-2 px-3 py-2 border rounded"><?= htmlspecialchars($edit_art['description'] ?? ''); ?></textarea>

    <div class="md:col-span-2 space-y-2">
        <input type="file" name="image" accept="image/*">
        <?php if ($edit_art && $edit_art['image']): ?>
        <img src="../uploads/<?= $edit_art['image']; ?>" class="w-20 h-20 rounded shadow">
        <?php endif; ?>
    </div>

    <div class="md:col-span-2">
      <button name="save_artwork" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">
        <?= $edit_art ? "Update Artwork" : "Add Artwork"; ?>
      </button>
    </div>

  </form>
</div>

<!-- LIST TABLE -->
<div class="bg-white border border-red-100 rounded-2xl shadow p-5">
    <h2 class="text-lg font-semibold text-red-700 mb-3">All Artworks</h2>

    <div class="overflow-x-auto text-sm">
        <table class="min-w-full">
            <thead>
                <tr class="bg-red-50 border-b">
                    <th class="px-3 py-2 text-left">ID</th>
                    <th class="px-3 py-2 text-left">Title</th>
                    <th class="px-3 py-2 text-left">Artist</th>
                    <th class="px-3 py-2 text-left">Category</th>
                    <th class="px-3 py-2 text-left">Price</th>
                    <th class="px-3 py-2 text-left">Image</th>
                    <th class="px-3 py-2 text-left">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($a = mysqli_fetch_assoc($arts)): ?>
                <tr class="border-b hover:bg-red-50">
                    <td class="px-3 py-2"><?= $a['id']; ?></td>
                    <td class="px-3 py-2"><?= htmlspecialchars($a['title']); ?></td>
                    <td class="px-3 py-2"><?= htmlspecialchars($a['artist']); ?></td>
                                    
                    <td class="px-3 py-2">
                        <?= htmlspecialchars($a['category_name'] ?? 'No Category'); ?>
                    </td>
                                    
                    <td class="px-3 py-2">₹<?= $a['price']; ?></td>
                                    
                    <td class="px-3 py-2">
                        <img src="../uploads/<?= $a['image']; ?>" class="w-12 h-12 rounded">
                    </td>

                    <td class="px-3 py-2 space-x-2">
                        <a href="manage_artworks.php?edit=<?= $a['id']; ?>"
                           class="text-xs px-3 py-1 border rounded border-blue-600 text-blue-600 hover:bg-blue-50">
                           Edit
                        </a>

                        <a onclick="return confirm('Are you sure?');"
                           href="manage_artworks.php?delete=<?= $a['id']; ?>"
                           class="text-xs px-3 py-1 border rounded border-red-600 text-red-600 hover:bg-red-50">
                           Delete
                        </a>
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
