<?php
session_start();
require_once "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$loggedInUser = $_SESSION['username'];

$query = "SELECT * FROM users WHERE username='$loggedInUser'";
$result = $conn->query($query);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $userId = $row['id'];
    $firstName = $row['first_name'];
    $lastName = $row['last_name'];
    $email = $row['email']; // Tambah field untuk email
} else {
    header("Location: login.php");
    exit();
}

$passwordError = "";
$passwordSuccess = "";
$emailError = "";
$emailSuccess = "";
$profileUpdateSuccess = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['new_first_name']) && isset($_POST['new_last_name'])) {
        $newFirstName = $_POST['new_first_name'];
        $newLastName = $_POST['new_last_name'];
        
        $updateProfileQuery = "UPDATE users SET first_name='$newFirstName', last_name='$newLastName' WHERE id='$userId'";
        
        if ($conn->query($updateProfileQuery)) {
            $profileUpdateSuccess = "Profile berhasil diperbarui.";
            $firstName = $newFirstName;
            $lastName = $newLastName;
        } else {
            $profileUpdateError = "Terjadi kesalahan saat memperbarui profile.";
        }
    }

    if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword !== $confirmPassword) {
            $passwordError = "Password baru dan konfirmasi password tidak cocok.";
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE users SET password='$hashedPassword' WHERE id='$userId'";
            
            if ($conn->query($updateQuery)) {
                $passwordSuccess = "Password berhasil diubah.";
            } else {
                $passwordError = "Terjadi kesalahan saat mengubah password.";
            }
        }
    }

    if (isset($_POST['new_email'])) {
        $newEmail = $_POST['new_email'];
        $updateEmailQuery = "UPDATE users SET email='$newEmail' WHERE id='$userId'";
        
        if ($conn->query($updateEmailQuery)) {
            $email = $newEmail;
            $emailSuccess = "Alamat email berhasil diubah.";
        } else {
            $emailError = "Terjadi kesalahan saat mengubah alamat email.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 500px; /* Adjust max-width */
            padding: 20px;
            background-color: white;
            border: 1px solid #ccc;
            box-sizing: border-box;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            text-align: left;
            margin-top: 10px;
        }

        .form-container label {
            display: inline-block;
            width: 120px;
            font-weight: bold;
            margin-right: 10px;
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button-container form {
            display: inline-block;
            margin: 0 10px;
        }

        .error-message {
            color: red;
            margin-top: 5px;
        }

        .success-message {
            color: green;
            margin-top: 5px;
        }

        /* Menyesuaikan tampilan tombol */
        .button-container input[type="submit"] {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        .button-container input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Profile</h2>
        <div class="form-container">
            <form action="" method="post">
                <label>Username:</label>
                <input type="text" value="<?php echo $loggedInUser; ?>" readonly><br>
                <label>First Name:</label>
                <input type="text" name="new_first_name" value="<?php echo $firstName; ?>" required><br>
                <label>Last Name:</label>
                <input type="text" name="new_last_name" value="<?php echo $lastName; ?>" required><br>
                <label>Email:</label>
                <input type="email" name="new_email" value="<?php echo $email; ?>" required><br>
                <h3>Update Data</h3>
                <?php if (isset($profileUpdateError)) : ?>
                    <p class="error-message"><?php echo $profileUpdateError; ?></p>
                <?php endif; ?>
                <?php if (isset($profileUpdateSuccess)) : ?>
                    <p class="success-message"><?php echo $profileUpdateSuccess; ?></p>
                <?php endif; ?>
                <label>New Password:</label>
                <input type="password" name="new_password"><br>
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password"><br>
                <input type="submit" value="Update Data">
            </form>
        </div>
        <div class="button-container">
            <form action="dashboard.php" method="get">
                <input type="submit" value="Back to Dashboard">
            </form>
            <form action="logout.php" method="post">
                <input type="submit" value="Logout">
            </form>
        </div>
    </div>
</body>
</html>
