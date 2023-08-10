<!DOCTYPE html>
<html>
<head>
    <title>Form Registrasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .registration-box {
            background-color: white;
            border: 1px solid #ccc;
            padding: 20px;
            box-sizing: border-box;
            width: 400px; /* Adjust width */
            display: flex; /* Display flex to put elements in a row */
            flex-direction: column; /* Stack elements vertically within flex container */
        }

        .registration-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .registration-box form {
            text-align: center;
            display: flex;
            flex-direction: column; /* Stack form elements vertically */
            align-items: center; /* Center-align form elements */
            width: 100%; /* Full width within flex container */
        }

        .registration-box label,
        .registration-box input {
            display: block;
            margin-bottom: 10px;
            padding: 10px; /* Adjust padding */
            box-sizing: border-box;
            width: 100%; /* Full width within form */
        }

        .registration-box input[type="submit"] {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        .registration-box input[type="submit"]:hover {
            background-color: #45a049;
        }

        .registration-box p {
            text-align: center;
            margin-top: 10px;
        }

        .registration-box a {
            color: #007bff;
        }
    </style>
    <script>
        // Cek apakah parameter username_taken ada di URL
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('username_taken')) {
            alert("Username telah terdaftar. Silakan gunakan username lain.");
        }
    </script>
</head>
<body>
    <div class="registration-box">
        <h2>Form Registrasi</h2>
        <form action="register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Alamat Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="first_name">Nama Depan:</label>
            <input type="text" id="first_name" name="first_name" required>
            <label for="last_name">Nama Belakang:</label>
            <input type="text" id="last_name" name="last_name" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Register">
        </form>
        <p>Sudah punya akun? <a href="index.php">Login di sini</a></p>
    </div>
</body>
</html>
