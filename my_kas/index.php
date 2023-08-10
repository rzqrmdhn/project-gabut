<!DOCTYPE html>
<html>
<head>
    <title>Form Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            border: 1px solid #ccc;
            padding: 20px;
            width: 300px;
            text-align: center;
            background-color: white;
        }

        .login-box h2 {
            margin-bottom: 20px;
        }

        .login-box form label {
            display: block;
            margin-bottom: 8px;
        }

        .login-box form input[type="text"],
        .login-box form input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .login-box form input[type="submit"] {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        .login-box form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .forgot-password {
            margin-top: 10px;
        }

        .forgot-password a {
            color: #007bff;
            text-decoration: none;
        }

        .registration-link {
            margin-top: 20px;
        }

        .registration-link a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Form Login</h2>
            <?php
            if (isset($_GET['unregistered'])) {
                echo '<p style="color: red;">User belum terdaftar.</p>';
            }
            if (isset($_GET['unauthorized'])) {
                echo '<p style="color: red;">Email atau password salah.</p>';
            }
            ?>
            <form action="process_login.php" method="post">
                <label>Email:</label>
                <input type="text" name="email" required><br><br> <!-- Ganti "username" menjadi "email" -->
                <label>Password:</label>
                <input type="password" name="password" required><br><br>
                <input type="submit" value="Login">
            </form>
            
            <div class="forgot-password">
                <a href="forgot_password.php">Lupa Password?</a>
            </div>
            
            <div class="registration-link">
                <h2>Belum punya akun?</h2>
                <a href="register_page.php">Registrasi di sini</a>
            </div>
        </div>
    </div>
</body>
</html>
