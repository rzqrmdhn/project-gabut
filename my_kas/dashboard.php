<!DOCTYPE html>
<html>
<head>
    <title>Chillax</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .profile-box {
            text-align: right;
            margin-bottom: 10px;
        }

        .profile-box p {
            margin: 0;
        }

        .profile-box form {
            display: inline-block;
        }

        .transaction-forms {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .transaction-form {
            flex: 0 0 48%;
            background-color: white;
            border: 1px solid #ccc;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
        }

        .transaction-form label,
        .transaction-form input,
        .transaction-form textarea {
            display: block;
            margin-bottom: 10px;
        }

        .transaction-history-box {
            background-color: white;
            border: 1px solid #ccc;
            padding: 20px;
            box-sizing: border-box;
            overflow-x: auto;
            margin-top: 20px;
        }

        .pagination {
            text-align: center;
            margin-top: 10px;
        }

        .pagination a {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 5px;
            background-color: #f4f4f4;
            border: 1px solid #ccc;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        .profile-box form input[type="submit"] {
            background-color: #4CAF50; /* Warna hijau untuk tombol Profile */
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
        }

        .profile-box form input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Gaya untuk tombol Logout */
        .profile-box form:nth-child(2) input[type="submit"] {
            background-color: #f44336; /* Warna merah untuk tombol Logout */
        }

        .profile-box form:nth-child(2) input[type="submit"]:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="profile-box">
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
                $loggedInUserId = $row['id']; // Tambahkan ini untuk mengambil user id
                $loggedInUserRole = $row['role'];
                $_SESSION['user_role'] = $loggedInUserRole;
            } else {
                header("Location: login.php");
                exit();
            }
            ?>
            <p>Welcome, <?php echo $loggedInUser; ?></p>
            <p>Role: <?php echo $loggedInUserRole; ?></p>
            <form action="profile_form.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo $loggedInUserId; ?>">
                <input type="submit" value="Profile">
            </form>
        </div>
        <h2>Chillax Finance</h2>


        <?php
        // Hanya tampilkan form untuk user dengan role 'user'
        if ($loggedInUserRole === 'user') { // Menghilangkan peran admin
            ?>
            <div class="transaction-forms">
                <div class="transaction-form">
                    <h3>Kas Masuk</h3>
                    <form action="process_transaction.php" method="post">
                        <input type="hidden" name="transaction_type" value="Kas Masuk">
                        <input type="hidden" name="user_id" value="<?php echo $loggedInUserId; ?>">
                        <label>Jumlah:</label>
                        <input type="text" name="amount" required>
                        <label>Keterangan:</label>
                        <textarea name="description"></textarea>
                        <input type="submit" value="Tambah Kas Masuk">
                    </form>
                </div>
                <div class="transaction-form">
                    <h3>Kas Keluar</h3>
                    <form action="process_transaction.php" method="post">
                        <input type="hidden" name="transaction_type" value="Kas Keluar">
                        <input type="hidden" name="user_id" value="<?php echo $loggedInUserId; ?>">
                        <label>Jumlah:</label>
                        <input type="text" name="amount" required>
                        <label>Keterangan:</label>
                        <textarea name="description"></textarea>
                        <input type="submit" value="Tambah Kas Keluar">
                    </form>
                </div>
            </div>
        <?php
        }
        ?>

        <div class="transaction-history-box">
            <h3>Riwayat Kas</h3>
            <form action="" method="get">
                <label>Filter Tanggal:</label>
                <input type="date" name="filter_date" value="<?php echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>">
                <?php
                if ($loggedInUserRole === 'admin' || $loggedInUserId === 1) {
                    echo '<label>Cari Pengguna:</label>';
                    echo '<input type="text" name="search_user" value="' . (isset($_GET['search_user']) ? $_GET['search_user'] : '') . '">';
                }
                ?>
                <input type="submit" value="Filter & Search">
            </form>
            <?php
            require_once "koneksi.php";

            $entriesPerPage = 10;
            $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($currentPage - 1) * $entriesPerPage;

            // Query untuk mengambil riwayat transaksi dengan informasi pengguna yang melakukan transaksi
            $query = "SELECT t.*, u.username FROM transactions AS t JOIN users AS u ON t.user_id = u.id";

            // Menambahkan filter berdasarkan tanggal
            if (isset($_GET['filter_date']) && !empty($_GET['filter_date'])) {
                $filterDate = $_GET['filter_date'];
                $query .= " WHERE DATE(t.created_at) = '$filterDate'";
            }

            // Menambahkan filter berdasarkan pengguna (hanya untuk admin)
            if ($loggedInUserRole === 'admin' && isset($_GET['search_user']) && !empty($_GET['search_user'])) {
                $searchUser = $_GET['search_user'];
                $query .= " AND u.username LIKE '%$searchUser%'";
            }

            // Menambahkan filter berdasarkan ID pengguna yang sedang login
            if ($loggedInUserRole === 'user') {
                $query .= " AND t.user_id = $loggedInUserId";
            }

            $query .= " ORDER BY t.created_at ASC LIMIT $offset, $entriesPerPage";

            $result = $conn->query($query);



    if ($result === false) {
        echo "Error executing query: " . $conn->error;
        exit();
    }

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>DEBIT / KREDIT</th><th>NOMINAL</th><th>Keterangan</th><th>Tanggal</th>";

        // Menampilkan kolom 'Pengguna' hanya jika peran pengguna adalah 'admin'
        if ($loggedInUserRole === 'admin') {
            echo "<th>Pengguna</th>";
        }

        echo "<th>Saldo Akhir</th></tr>";

        $saldo = 0;
        $count = 0;
        while ($row = $result->fetch_assoc()) {
            if ($count >= $entriesPerPage) {
                break;
            }
            $amount = ($row['type'] == 'Kas Masuk') ? $row['amount'] : -$row['amount'];
            $saldo += $amount;

            // Format angka menjadi mata uang rupiah
            $formattedAmount = "Rp " . number_format($amount, 2, ',', '.');
            $formattedSaldo = "Rp " . number_format($saldo, 2, ',', '.');

            $transactionUserQuery = "SELECT username FROM users WHERE id = {$row['user_id']}";
            $transactionUserResult = $conn->query($transactionUserQuery);
            $transactionUser = ($transactionUserResult->num_rows === 1) ? $transactionUserResult->fetch_assoc()['username'] : "N/A";

            echo "<tr><td>" . (($row['type'] == 'Kas Masuk') ? 'DEBIT' : 'KREDIT') . "</td><td>{$formattedAmount}</td><td>{$row['description']}</td><td>{$row['created_at']}</td>";

            // Menampilkan kolom 'Pengguna' hanya jika peran pengguna adalah 'admin'
            if ($loggedInUserRole === 'admin') {
                echo "<td>{$transactionUser}</td>";
            }

            echo "<td>{$formattedSaldo}</td></tr>";
            $count++;
        }
        echo "</table>";

        // Tambahkan kolom saldo akhir
        echo "<table>";
        echo "<tr><th>Saldo Akhir</th></tr>";
        echo "<tr><td>{$formattedSaldo}</td></tr>";
        echo "</table>";

        // Pagination
        $queryCount = "SELECT COUNT(id) AS total FROM transactions WHERE user_id = $loggedInUserId"; // Ubah query untuk menghitung jumlah transaksi milik user
        $resultCount = $conn->query($queryCount);
        $rowCount = $resultCount->fetch_assoc()['total'];
        $totalPages = ceil($rowCount / $entriesPerPage);

        echo "<div class='pagination'>";
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='?page=$i'>$i</a>";
        }
        echo "</div>";
    } else {
        echo "Tidak ada data riwayat kas.";
    }

    $conn->close();
    ?>
</div>
</div>
</body>
</html>