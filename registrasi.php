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
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $level_id = isset($_POST['level_id']) ? $_POST['level_id'] : '';

    if (!empty($name) && !empty($level_id)) {
        $sql = "INSERT INTO user (name, level_id) VALUES (:name, :level_id)";
        $stmt = $pdo->prepare($sql);

        try {
            if ($stmt->execute(['name' => $name, 'level_id' => $level_id])) {
                header("Location: index.php");
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


$levelQuery = $pdo->query('SELECT id, name FROM level');
$levelResults = $levelQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pelanggan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Registrasi Pelanggan</h2>
        <form action="registrasi.php" method="post">
            <input type="hidden" name="create" value="1">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="level_id">Level ID:</label>
                <select class="form-control" id="level_id" name="level_id" required>
                    <option value="">Pilih level</option>
                    <?php foreach ($levelResults as $level) : ?>
                        <option value="<?= htmlspecialchars($level['id']) ?>">
                            <?= htmlspecialchars($level['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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