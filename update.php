<?php

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

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $pelanggan_id = $_POST['pelanggan_id'];
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $daya = $_POST['daya'];
    $pemakaian_kwh = $_POST['pemakaian_kwh'];

    $sql = "UPDATE penggunaan SET pelanggan_id = :pelanggan_id, bulan = :bulan, tahun = :tahun, daya = :daya, pemakaian_kwh = :pemakaian_kwh WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['pelanggan_id' => $pelanggan_id, 'bulan' => $bulan, 'tahun' => $tahun, 'daya' => $daya, 'pemakaian_kwh' => $pemakaian_kwh, 'id' => $id]);

    header('Location: index.php');
    exit;
}


$id = $_GET['id'];
$sql = "SELECT * FROM penggunaan WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Penggunaan Listrik</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="mt-4">Update Data Penggunaan Listrik</h2>
        <form action="update.php" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($record['id']) ?>">
            <div class="form-group">
                <label for="pelanggan_id">Pelanggan ID:</label>
                <input type="text" class="form-control" id="pelanggan_id" name="pelanggan_id" value="<?= htmlspecialchars($record['pelanggan_id']) ?>" required>
            </div>
            <div class="form-group">
                <label for="bulan">Bulan:</label>
                <input type="text" class="form-control" id="bulan" name="bulan" value="<?= htmlspecialchars($record['bulan']) ?>" required>
            </div>
            <div class="form-group">
                <label for="tahun">Tahun:</label>
                <input type="text" class="form-control" id="tahun" name="tahun" value="<?= htmlspecialchars($record['tahun']) ?>" required>
            </div>
            <div class="form-group">
                <label for="daya">Daya:</label>
                <input type="text" class="form-control" id="daya" name="daya" value="<?= htmlspecialchars($record['daya']) ?>" required>
            </div>
            <div class="form-group">
                <label for="pemakaian_kwh">Pemakaian KWh:</label>
                <input type="text" class="form-control" id="pemakaian_kwh" name="pemakaian_kwh" value="<?= htmlspecialchars($record['pemakaian_kwh']) ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>