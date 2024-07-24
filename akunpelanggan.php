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

if (isset($_POST['create'])) {

    if (isset($_POST['user_id'], $_POST['address']) && !empty($_POST['user_id']) && !empty($_POST['address'])) {
        $user_id = $_POST['user_id'];
        $address = $_POST['address'];

        $sql = "INSERT INTO pelanggan (user_id, address) VALUES (:user_id, :address)";
        $stmt = $pdo->prepare($sql);

        try {
            if ($stmt->execute(['user_id' => $user_id, 'address' => $address])) {
                header("Location: index.php");
                exit;
            } else {
                echo "Error: Could not execute the query";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error: User ID and Address are required fields";
    }
}
$userQuery = $pdo->query('SELECT id, name FROM user');
$userResults = $userQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Pelanggan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Buat Akun Pelanggan</h2>
        <form action="akunpelanggan.php" method="post">
            <input type="hidden" name="create" value="1">
            <div class="form-group">
                <label for="user_id">Nama Pelanggan:</label>
                <select class="form-control" id="user_id" name="user_id" required>
                    <option value="">Pilih Pelanggan</option>
                    <?php foreach ($userResults as $user) : ?>
                        <option value="<?= htmlspecialchars($user['id']) ?>">
                            <?= htmlspecialchars($user['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="address">Alamat:</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <button type="submit" class="btn btn-primary">Buat</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>