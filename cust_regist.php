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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f2f5;
            font-family: 'Arial', sans-serif;
        }

        .form {
            background-color: #fff;
            display: block;
            padding: 2rem;
            max-width: 350px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .input-container {
            margin-bottom: 1rem;
        }

        .input-container input {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s;
        }

        .input-container input:focus {
            border-color: #4F46E5;
            outline: none;
        }

        .submit {
            display: block;
            width: 100%;
            padding: 0.75rem;
            background-color: #4F46E5;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit:hover {
            background-color: #3b37b3;
        }

        .login-link {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.875rem;
            color: #666;
        }

        .login-link a {
            color: #4F46E5;
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-link a:hover {
            color: #333;
        }
    </style>
</head>

<body>
    <form action="" method="post" class="form">
        <p class="form-title">Registrasi Akun</p>
        <div class="input-container">
            <input type="text" id="username" name="username" placeholder="Nama Pengguna" required>
        </div>
        <div class="input-container">
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <div class="input-container">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Password" required>
        </div>
        <button type="submit" class="submit">Daftar</button>
        <p class="login-link">
            Sudah punya akun?
            <a href="cust_login.php">Masuk</a>
        </p>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];


        if ($password !== $confirm_password) {
            echo "<p style='color:red; text-align:center;'>Password dan Konfirmasi Password tidak cocok!</p>";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);


            $sql = "INSERT INTO user (name, password, level_id) VALUES (?, ?, 2)";
            $stmt = $pdo->prepare($sql);

            try {
                $stmt->execute([$username, $hashed_password]);
                echo "<p style='color:green; text-align:center;'>Registrasi berhasil! Anda sekarang dapat <a href='cust_login.php'>masuk</a>.</p>";
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    echo "<p style='color:red; text-align:center;'>Nama pengguna sudah terdaftar!</p>";
                } else {
                    echo "<p style='color:red; text-align:center;'>Terjadi kesalahan: " . $e->getMessage() . "</p>";
                }
            }
        }
    }
    ?>
</body>

</html>