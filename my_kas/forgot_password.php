<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .reset-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .reset-box {
            border: 1px solid #ccc;
            padding: 20px;
            width: 300px;
            text-align: center;
            background-color: white;
        }

        .reset-box h2 {
            margin-bottom: 20px;
        }

        .reset-box form label {
            display: block;
            margin-bottom: 8px;
        }

        .reset-box form input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .reset-box form input[type="submit"] {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        .reset-box form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .reset-box p {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-box">
            <h2>Reset Password</h2>
            <?php
            // Mengambil token reset password dari URL
            $resetToken = isset($_GET['token']) ? $_GET['token'] : '';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                require_once "koneksi.php";

                // Validasi dan ambil alamat email dari form
                $email = $_POST['email'];

                // Cek apakah alamat email terdaftar
                $emailCheckQuery = "SELECT * FROM users WHERE email='$email'";
                $emailCheckResult = $conn->query($emailCheckQuery);

                if ($emailCheckResult->num_rows === 0) {
                    echo '<p style="color: red;">Alamat email tidak terdaftar.</p>';
                } else {
                    // Kode untuk mengirim email reset password
                    // Ganti dengan kode untuk mengirim email, misalnya menggunakan PHPMailer
                    // Contoh: https://github.com/PHPMailer/PHPMailer
                    echo '<p style="color: green;">Silakan cek email Anda untuk instruksi lebih lanjut.</p>';
                }

                $conn->close();
            }
            ?>
            <form action="" method="post">
                <label>Alamat Email:</label>
                <input type="email" name="email" required><br><br>
                <input type="submit" value="Reset Password">
            </form>
        </div>
    </div>
</body>
</html>
