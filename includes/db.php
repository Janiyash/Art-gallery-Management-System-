<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "art_gallery_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("DB Connection failed: " . mysqli_connect_error());
}
?>
