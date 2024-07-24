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
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f2f5;
        }

        .form {
            background-color: #fff;
            display: block;
            padding: 1rem;
            max-width: 350px;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .form-title {
            font-size: 1.25rem;
            line-height: 1.75rem;
            font-weight: 600;
            text-align: center;
            color: #000;
        }

        .input-container {
            position: relative;
        }

        .input-container input,
        .input-container select,
        .form button {
            outline: none;
            border: 1px solid #e5e7eb;
            margin: 8px 0;
        }

        .input-container input {
            background-color: #fff;
            padding: 1rem;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            width: 300px;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .input-container select {
            background-color: #fff;
            padding: 1rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            width: 300px;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .submit {
            display: block;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
            background-color: #4F46E5;
            color: #ffffff;
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 500;
            width: 100%;
            border-radius: 0.5rem;
            text-transform: uppercase;
        }

        .signup-link {
            color: #6B7280;
            font-size: 0.875rem;
            line-height: 1.25rem;
            text-align: center;
        }

        .signup-link a {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <form action="" method="post" class="form">
        <p class="form-title">Daftar Akun</p>
        <div class="input-container">
            <input type="text" id="username" name="username" placeholder="Nama Pengguna" required><br><br>
        </div>
        <div class="input-container">
            <input type="password" id="password" name="password" placeholder="Password" required><br><br>
        </div>
        <div class="input-container">
            <select id="level_id" name="level_id" required>
                <option value="">Pilih Level</option>
                <?php

                $levelQuery = $pdo->query('SELECT id, name FROM level');
                $levelResults = $levelQuery->fetchAll(PDO::FETCH_ASSOC);
                foreach ($levelResults as $level) {
                    echo '<option value="' . htmlspecialchars($level['id']) . '">' . htmlspecialchars($level['name']) . '</option>';
                }
                ?>
            </select>
        </div>
        <br><br>
        <input type="submit" value="Daftar" class="submit">
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $level_id = $_POST['level_id'];

            $passwordHash = password_hash($password, PASSWORD_BCRYPT);


            $sqlCheckUsername = "SELECT * FROM g_akun WHERE username = ?";
            $stmtCheckUsername = $pdo->prepare($sqlCheckUsername);
            $stmtCheckUsername->execute([$username]);
            $resultUsername = $stmtCheckUsername->fetch(PDO::FETCH_ASSOC);

            if ($resultUsername) {
                echo "Username Sudah Digunakan!";
            } else {

                $sqlInsertGakun = "INSERT INTO g_akun (username, password) VALUES (?, ?)";
                $stmtInsertGakun = $pdo->prepare($sqlInsertGakun);
                if ($stmtInsertGakun->execute([$username, $passwordHash])) {
                    echo "Pendaftaran Akun Sukses!";
                } else {
                    echo "Error: " . $stmtInsertGakun->errorInfo()[2];
                }


                $g_akun_id = $pdo->lastInsertId();

                $sqlInsertUser = "INSERT INTO user (name, password, level_id) VALUES (?, ?, ?)";
                $stmtInsertUser = $pdo->prepare($sqlInsertUser);
                if ($stmtInsertUser->execute([$username, $passwordHash, $level_id])) {
                    echo "Sekarang kamu bisa <a href='login.php'>Masuk</a>.";
                } else {
                    echo "Error: " . $stmtInsertUser->errorInfo()[2];
                }
            }
        }
        ?>
    </form>


</body>

</html>