<?php
session_start();
require_once "includes/db.php";
include "includes/header.php";
require_once "includes/mail.php";   // <-- IMPORTANT (Load your mail system)

// CONTACT FORM HANDLER
$success_msg = "";
$error_msg = "";

if (isset($_POST['send_contact'])) {

    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $message = nl2br(mysqli_real_escape_string($conn, $_POST['message']));

    // SUBJECT OF MAIL
    $subject = "New Contact Message from $name";

    // HTML BODY OF EMAIL
    $body = "
        <h2 style='color:#e11d22;'>New Contact Message</h2>

        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Message:</strong><br>$message</p>

        <br><br>
        <p style='color:#555;'>Artify Gallery</p>
    ";

    // SEND EMAIL TO ADMIN
    if (sendContactMail("janiyash0911@gmail.com", $subject, $body)) {
        $success_msg = "Message sent successfully!";
    } else {
        $error_msg = "Failed to send your message. Please try again.";
    }
}
?>


<?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
<div class="bg-green-50 text-green-700 py-2 text-center">
    Logged out successfully!
</div>
<?php endif; ?>

<!-- HERO SECTION -->
<section id="home" class="bg-gradient-to-r from-red-50 via-red-100 to-white">
  <div class="max-w-6xl mx-auto px-4 py-16 grid md:grid-cols-2 gap-10 items-center">

    <div class="space-y-4">
      <p class="uppercase tracking-[0.2em] text-[11px] text-red-600/80">
        Exclusive • Curated • Premium
      </p>

      <h1 class="text-4xl md:text-5xl font-bold leading-tight text-gray-900">
        Discover & Own<br> Premium Artworks
      </h1>

      <p class="text-sm text-gray-700 max-w-md">
        Explore original paintings, illustrations, and digital art from top creators worldwide.
      </p>

      <div class="flex gap-3 pt-2">
        <a href="gallery.php"
           class="px-6 py-2.5 rounded-full bg-red-600 text-white text-sm hover:bg-red-700 shadow">
          Browse Gallery
        </a>

        <a href="#about"
           class="px-6 py-2.5 rounded-full border border-red-400 text-red-700 text-sm hover:bg-red-50">
          Learn More
        </a>
      </div>
    </div>

    <div class="relative">
      <div class="rounded-3xl overflow-hidden shadow-xl border border-red-200 bg-white">
        <img src="assets/banner.jpeg" class="w-full h-full object-cover">
      </div>
      <div class="absolute -bottom-4 -left-4 bg-white/90 backdrop-blur px-4 py-2 rounded-xl text-[11px] border border-red-200 shadow">
        <p class="font-semibold text-red-700">Featured Collection</p>
        <p class="text-gray-600">Crimson Dreams · 12 artworks</p>
      </div>
    </div>

  </div>
</section>

<!-- FEATURED ARTWORKS -->
<section class="max-w-6xl mx-auto px-4 py-14">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-900">Featured Artworks</h2>
    <a href="gallery.php" class="text-xs text-red-600 hover:text-red-800">View all →</a>
  </div>

  <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8">

    <?php
    // ✔ FIXED: Correct column names (id, category_id)
    $feat = mysqli_query($conn, "
        SELECT a.*, c.name AS category
        FROM artworks a
        LEFT JOIN categories c ON a.category_id = c.id
        ORDER BY a.created_at DESC
        LIMIT 6
    ");

    while ($art = mysqli_fetch_assoc($feat)):
    ?>

    <div class="bg-white border border-red-100 rounded-2xl shadow-sm hover:shadow-lg transition hover:-translate-y-1">
      <img src="uploads/<?= $art['image']; ?>" class="w-full h-48 object-cover rounded-t-2xl">

      <div class="p-5 space-y-2">
        <p class="text-xs text-red-600 font-medium"><?= $art['category']; ?></p>

        <h3 class="text-lg font-semibold text-gray-900"><?= $art['title']; ?></h3>

        <p class="text-sm text-gray-600">By <?= $art['artist']; ?></p>

        <p class="text-lg font-bold text-red-600">₹<?= $art['price']; ?></p>

        <a href="purchase.php?artwork_id=<?= $art['id']; ?>"
           class="block w-full mt-2 px-4 py-2 text-center text-sm
                  bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold
                  rounded-full shadow-md hover:from-red-700 hover:to-red-600 transition">
            Purchase Now
        </a>
      </div>
    </div>

    <?php endwhile; ?>

  </div>
</section>

<!-- ABOUT SECTION -->
<section id="about" class="bg-red-50 py-14">
  <div class="max-w-6xl mx-auto px-4">

    <h2 class="text-2xl font-semibold text-gray-900 mb-3">About Artify Gallery</h2>

    <p class="text-sm text-gray-700 max-w-3xl leading-relaxed">
      Artify Gallery brings premium artwork collections from artists across the world.
      Each piece is handpicked for originality, creativity, and aesthetic value.
    </p>

  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="py-16 bg-gradient-to-b from-red-50 via-white to-red-50">
  <div class="max-w-6xl mx-auto px-4">

    <h2 class="text-3xl font-bold text-center text-gray-900 mb-3">Contact Us</h2>

    <p class="text-center text-gray-600 max-w-2xl mx-auto mb-12">
      Have questions, want custom artwork, or interested in bulk purchases?<br>
      Get in touch with us.
    </p>

    <div class="grid md:grid-cols-2 gap-10">

      <!-- Left -->
      <div class="bg-white border border-red-200 rounded-3xl p-8 shadow-sm">
        <h3 class="text-xl font-semibold text-red-700 mb-5">Artify Gallery</h3>

        <div class="space-y-4 text-gray-700 text-sm">
          <p class="flex items-start gap-3"><span class="text-red-600 text-lg">📍</span> Vadodara, Gujarat (India)</p>
          <p class="flex items-start gap-3"><span class="text-red-600 text-lg">📞</span> +91 99999 99999</p>
          <p class="flex items-start gap-3"><span class="text-red-600 text-lg">📧</span> support@artifygallery.com</p>
          <p class="flex items-start gap-3"><span class="text-red-600 text-lg">⏱️</span> 10:00 AM – 7:00 PM (Mon–Sun)</p>
        </div>
      </div>

      <!-- Form -->
<form action="#contact" method="POST"
      class="bg-white border border-red-200 rounded-3xl p-8 shadow-sm space-y-5">

    <input name="name" class="w-full px-4 py-3 rounded-xl border border-red-200" placeholder="Your Name" required>

    <input name="email" type="email" class="w-full px-4 py-3 rounded-xl border border-red-200" placeholder="Your Email" required>

    <textarea name="message" rows="4" class="w-full px-4 py-3 rounded-xl border border-red-200" placeholder="Your Message" required></textarea>

    <button name="send_contact"
            class="w-full py-3 bg-red-600 text-white rounded-xl text-base font-semibold hover:bg-red-700 transition">
      Send Message
    </button>

</form>


    </div>

  </div>
</section>

<?php if (!empty($success_msg)): ?>
<div id="successPopup"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 animate-fadeIn">

  <div class="bg-gradient-to-br from-white via-red-50 to-red-100 border border-red-300 shadow-xl rounded-2xl p-8 max-w-sm w-full text-center animate-popupScale">

    <div class="w-16 h-16 mx-auto mb-4 bg-red-100 border border-red-300 rounded-full flex items-center justify-center shadow-lg">
      ✔
    </div>

    <h2 class="text-xl font-semibold text-red-700 mb-1">Message Sent!</h2>
    <p class="text-sm text-gray-600 mb-5">Thank you for contacting us.</p>

    <button onclick="document.getElementById('successPopup').style.display='none';"
            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
      Close
    </button>

  </div>

</div>
<?php endif; ?>
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
