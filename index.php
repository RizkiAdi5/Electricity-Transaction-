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

if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM penggunaan WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}

$sql = "SELECT penggunaan.id, penggunaan.pelanggan_id, user.name AS user_name, penggunaan.bulan, penggunaan.tahun, penggunaan.daya, penggunaan.pemakaian_kwh, penggunaan.nomor_meter, penggunaan.total_dibayarkan, penggunaan.metode_pembayaran, penggunaan.status_pembayaran, penggunaan.nomor_token
        FROM penggunaan
        LEFT JOIN user ON penggunaan.pelanggan_id = user.id";
$query = $pdo->query($sql);
$results = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script>
        function confirmLogout(event) {
            event.preventDefault(); // Prevent the default action
            if (confirm("Apakah anda yakin ingin keluar?")) {
                window.location.href = event.target.href; // Redirect if user confirms
            }
        }
    </script>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar" style="background-color: #535C91;">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <h2 class="text-center  text-light p-2 mt-3">
                            Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                        <li class="nav-item d-flex justify-content-center align-items-center">
                            <img src="listrik.png" alt="Dashboard Icon" class="img-fluid mb-5" width="100px" height="100px">
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active text-light" href="index.php">
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-light" href="inputdata.php">
                                Input Data Penggunaan Listrik Pelanggan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="akunpelanggan.php">
                                Tambah Informasi Pelanggan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="infopelanggan.php">
                                Pusat Data Pengguna
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="# I Use External Website Like Crisp" target="_blank">
                                Customer Service
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light" href="logout.php" onclick="confirmLogout(event)">
                                Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Data Penggunaan Listrik</h1>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm" border="1px solid black">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan ID</th>
                                <th>Nama Pelanggan</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Daya</th>
                                <th>Pemakaian KWh</th>
                                <th>Nomor Meter</th>
                                <th>Total Dibayarkan</th>
                                <th>Metode Pembayaran</th>
                                <th>Status Pembayaran</th>
                                <th>Nomor Token</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($results) > 0) : ?>
                                <?php foreach ($results as $row) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                        <td><?= htmlspecialchars($row['pelanggan_id']) ?></td>
                                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                                        <td><?= htmlspecialchars($row['bulan']) ?></td>
                                        <td><?= htmlspecialchars($row['tahun']) ?></td>
                                        <td><?= htmlspecialchars($row['daya']) ?></td>
                                        <td><?= htmlspecialchars($row['pemakaian_kwh']) ?></td>
                                        <td><?= htmlspecialchars($row['nomor_meter']) ?></td>
                                        <td><?= htmlspecialchars($row['total_dibayarkan']) ?></td>
                                        <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                                        <td><?= htmlspecialchars($row['status_pembayaran']) ?></td>
                                        <td><?= htmlspecialchars($row['nomor_token']) ?></td>
                                        <td>
                                            <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="post" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <button type="submit" name="delete" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="13">Data tidak ditemukan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
