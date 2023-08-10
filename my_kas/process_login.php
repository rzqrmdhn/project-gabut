<?php
session_start();
require_once "koneksi.php";

// Ambil data dari form login
$email = $_POST['email']; // Ganti "username" menjadi "email"
$password = $_POST['password'];

// Query untuk mendapatkan data pengguna berdasarkan email
$query = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($query);

// Cek apakah email terdaftar dalam database
if ($result->num_rows == 1) {
    $userRow = $result->fetch_assoc();
    $hashedPassword = $userRow['password'];

    // Verifikasi password
    if (password_verify($password, $hashedPassword)) {
        $_SESSION['user_id'] = $userRow['id'];
        $_SESSION['username'] = $userRow['username'];
        header("Location: dashboard.php"); // Redirect ke dashboard jika login berhasil
    } else {
        header("Location: index.php?unauthorized=true"); // Redirect dengan pesan error jika password salah
    }
} else {
    header("Location: index.php?unregistered=true"); // Redirect dengan pesan error jika email tidak terdaftar
}

$conn->close();
?>
