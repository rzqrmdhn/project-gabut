<?php
require_once "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email']; // Ambil email dari form
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];

    // Periksa apakah username atau email sudah terdaftar
    $checkQuery = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        header("Location: register_page.php?username_taken=1"); // Redirect dengan parameter username_taken
        exit();
    }

    // Jika username dan email belum terdaftar, lakukan proses registrasi
    $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'user')";
    
    if ($conn->query($query) === TRUE) {
        // Ambil ID user yang baru ditambahkan
        $newUserId = $conn->insert_id;

        // Tambahkan data ke tabel my_kas
        $kasQuery = "INSERT INTO my_kas (user_id, first_name, last_name, saldo) VALUES ($newUserId, '$firstName', '$lastName', 0)";
        $conn->query($kasQuery);

        header("Location: index.php?registered=1"); // Kembali ke halaman login dengan pesan sukses registrasi
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

$conn->close();
?>
