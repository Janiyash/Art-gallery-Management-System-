<?php
session_start();
require "includes/db.php";

$error = "";

if (isset($_POST['login'])) {

    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Get user by email
    $q    = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' LIMIT 1");
    $user = mysqli_fetch_assoc($q);

    if ($user) {

        // Verify password
        if (password_verify($password, $user['password'])) {

            // ✅ CORRECT SESSION VARIABLES
            $_SESSION['id']    = $user['id'];      // matches users.id
            $_SESSION['name']  = $user['name'];
            $_SESSION['role']  = $user['role'];    // 'admin' or 'user'
            $_SESSION['email'] = $user['email'];

            // Redirect to home
            header("Location: index.php");
            exit;

        } else {
            $error = "Incorrect password!";
        }

    } else {
        $error = "Email not registered!";
    }
}
?>

<?php include "includes/header.php"; ?>

<!-- LOGIN FORM SECTION -->
<section class="min-h-[70vh] flex items-center justify-center bg-gradient-to-b from-red-50 via-white to-red-100 px-4 py-12">
    
    <div class="bg-white w-full max-w-md rounded-3xl border border-red-200 shadow-md p-10 text-center">

        <h1 class="text-3xl font-semibold text-red-700">Welcome Back</h1>
        <p class="text-gray-600 text-sm mt-1 mb-8">Login to continue your art journey</p>

        <!-- ERROR MESSAGE -->
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border border-red-300 text-red-700 p-2 rounded mb-4 text-sm">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">

            <input type="email" name="email" required
                class="w-full px-4 py-3 rounded-xl border border-red-200 focus:ring-2 focus:ring-red-400 outline-none"
                placeholder="Email Address">

            <input type="password" name="password" required
                class="w-full px-4 py-3 rounded-xl border border-red-200 focus:ring-2 focus:ring-red-400 outline-none"
                placeholder="Password">

            <button name="login"
                class="w-full py-3 bg-red-600 text-white rounded-xl text-lg font-medium hover:bg-red-700 transition">
                Login
            </button>

        </form>

        <p class="mt-6 text-gray-700 text-sm">
            Don't have an account?
            <a href="register.php" class="text-red-600 font-semibold hover:underline">Register</a>
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
