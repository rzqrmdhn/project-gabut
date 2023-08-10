<?php
require_once "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transactionType = $_POST['transaction_type'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $userId = $_POST['user_id']; // Ambil user_id dari form

    $query = "INSERT INTO transactions (user_id, type, amount, description) VALUES ('$userId', '$transactionType', '$amount', '$description')";
    
    if ($conn->query($query) === TRUE) {
        header("Location: dashboard.php"); // Kembali ke halaman dashboard setelah transaksi berhasil
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

$conn->close();
?>
