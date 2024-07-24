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


if (isset($_POST['update_pelanggan'])) {
    $id = $_POST['id'];
    $user_id = $_POST['user_id'];
    $address = $_POST['address'];

    $sql = "UPDATE pelanggan SET user_id = :user_id, address = :address WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id, 'address' => $address, 'id' => $id]);
}


if (isset($_POST['delete_pelanggan'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM pelanggan WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}


if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $level_id = $_POST['level_id'];


    $sql = "UPDATE user SET name = :name, level_id = :level_id WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $name, 'level_id' => $level_id, 'id' => $id]);
}


if (isset($_POST['delete_user'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM user WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}

if (isset($_POST['create'])) {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $level_id = isset($_POST['level_id']) ? $_POST['level_id'] : '';

    if (!empty($name) && !empty($level_id)) {
        $sql = "INSERT INTO user (name, level_id) VALUES (:name, :level_id)";
        $stmt = $pdo->prepare($sql);

        try {
            if ($stmt->execute(['name' => $name, 'level_id' => $level_id])) {
                header("Location: infopelanggan.php");
                exit;
            } else {
                echo "Error: Could not execute the query";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error: Name and Level ID are required fields";
    }
}


session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$query = $pdo->query('
    SELECT p.id, p.user_id, u.name as user_name, l.name as level_name, p.address
    FROM pelanggan p
    JOIN user u ON p.user_id = u.id
    JOIN level l ON u.level_id = l.id
');
$pelangganResults = $query->fetchAll(PDO::FETCH_ASSOC);


$levelQuery = $pdo->query('SELECT id, name FROM level');
$levelResults = $levelQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Pelanggan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
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
                        <h2 class="text-center  text-light p-2 mt-3">Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
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
                            <a class="nav-link text-light" href="https://app.crisp.chat/website/018bc410-5f1c-4ff2-be7c-1ac608cbd27a/inbox/session_c8c70922-ee8d-42e8-8c6a-786386c76944/" target="_blank">
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
                    <h1 class="h2">Informasi Seluruh Pengguna</h1>
                </div>

                <div class="table-responsive">
                    <h3>Data Pengguna</h3>
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan ID</th>
                                <th>Nama</th>
                                <th>Level</th>
                                <th>Alamat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($pelangganResults) > 0) : ?>
                                <?php foreach ($pelangganResults as $row) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                        <td><?= htmlspecialchars($row['user_id']) ?></td>
                                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                                        <td><?= htmlspecialchars($row['level_name']) ?></td>
                                        <td><?= htmlspecialchars($row['address']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5">No results found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="table-responsive">
                    <p>Registrasi Pengguna Baru  <a href="registrasi_akun.php">Sekarang</a></p>

                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>