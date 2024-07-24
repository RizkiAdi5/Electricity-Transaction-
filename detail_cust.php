<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$host = 'localhost';
$dbname = 'electricity_payment';
$username = 'root';
$password = '';

try {

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}


$id = isset($_GET['id']) ? $_GET['id'] : null;


$sql = "SELECT penggunaan.*, user.name AS user_name FROM penggunaan LEFT JOIN user ON penggunaan.pelanggan_id = user.id WHERE penggunaan.id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Data tidak ditemukan");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penggunaan Listrik</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Detail Penggunaan Listrik</h1>
        <div class="row">
            <div class="col-md-6">
                <p><strong>ID:</strong> <?= htmlspecialchars($row['id']) ?></p>
                <p><strong>Pelanggan ID:</strong> <?= htmlspecialchars($row['pelanggan_id']) ?></p>
                <p><strong>Nama Pelanggan:</strong> <?= htmlspecialchars($row['user_name']) ?></p>
                <p><strong>Bulan:</strong> <?= htmlspecialchars($row['bulan']) ?></p>
                <p><strong>Tahun:</strong> <?= htmlspecialchars($row['tahun']) ?></p>
                <p><strong>Daya:</strong> <?= htmlspecialchars($row['daya']) ?></p>
                <p><strong>Pemakaian KWh:</strong> <?= htmlspecialchars($row['pemakaian_kwh']) ?></p>
                <p><strong>Nomor Meter:</strong> <?= htmlspecialchars($row['nomor_meter']) ?></p>
                <p><strong>Total Dibayarkan:</strong> <?= htmlspecialchars($row['total_dibayarkan']) ?></p>
                <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($row['metode_pembayaran']) ?></p>
                <p><strong>Status Pembayaran:</strong> <?= htmlspecialchars($row['status_pembayaran']) ?></p>
                <p><strong>Nomor Token:</strong> <?= htmlspecialchars($row['nomor_token']) ?></p>
            </div>
        </div>
        <a href="b_customer.php" class="btn btn-secondary">Kembali</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>