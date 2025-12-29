<?php
session_start();
require_once __DIR__ . "/includes/db.php";

$error = "";
$success = "";

if (isset($_POST['register'])) {

    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");

    if (mysqli_num_rows($check) > 0) {
        $error = "Email already registered!";
    } else {

        // Insert user
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if (mysqli_query($conn, $sql)) {
            
            // Redirect after successful register
            header("Location: login.php");   // ← OR change to index.php
            exit;

        } else {
            $error = "Something went wrong!";
        }
    }
}

include "includes/header.php";
?>

<section class="min-h-[70vh] flex items-center justify-center bg-gradient-to-b from-red-50 via-white to-red-100 px-4 py-12">

<div class="bg-white w-full max-w-md rounded-3xl border border-red-200 shadow-md p-10 text-center">

<h1 class="text-3xl font-semibold text-red-700">Create Account</h1>
<p class="text-gray-600 text-sm mt-1 mb-8">Join Artify to explore premium artworks</p>

<?php if (!empty($error)): ?>
<div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-sm"><?= $error ?></div>
<?php endif; ?>

<form method="POST" class="space-y-4">

<input type="text" name="name" required
       class="w-full px-4 py-3 rounded-xl border border-red-200 focus:ring-2 focus:ring-red-400 outline-none"
       placeholder="Full Name">

<input type="email" name="email" required
       class="w-full px-4 py-3 rounded-xl border border-red-200 focus:ring-2 focus:ring-red-400 outline-none"
       placeholder="Email Address">

<input type="password" name="password" required
       class="w-full px-4 py-3 rounded-xl border border-red-200 focus:ring-2 focus:ring-red-400 outline-none"
       placeholder="Password">

<button name="register"
        class="w-full py-3 bg-red-600 text-white rounded-xl text-lg font-medium hover:bg-red-700 transition">
    Register
</button>

</form>

<p class="mt-6 text-gray-700 text-sm">Already have an account?
    <a href="login.php" class="text-red-600 font-semibold hover:underline">Login</a>
</p>

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
