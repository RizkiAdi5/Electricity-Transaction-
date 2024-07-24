<?php

session_start();

$host = 'localhost';
$dbname = 'electricity_payment';
$username = 'root';
$password = '';

try {

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT g.*, u.id as user_id, u.level_id
            FROM g_akun g
            LEFT JOIN user u ON g.username = u.name
            WHERE g.username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['user_id'];


        if ($user['level_id'] == 2) {
            echo "<script>alert('Selamat! Anda memiliki akses sebagai pengguna.'); window.location.href = 'b_customer.php';</script>";
          
            exit();
        } else {
            echo "<script>alert('Selamat! Anda memiliki akses sebagai admin.'); window.location.href = 'index.php';</script>";
            exit();
            
        }
    } else {

        echo "Nama pengguna atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        .submit {
            display: block;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
            background-color: #4f46e5;
            color: #ffffff;
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 500;
            width: 100%;
            border-radius: 0.5rem;
            text-transform: uppercase;
            cursor: pointer;
        }

        .signup-link {
            color: #6b7280;
            font-size: 0.875rem;
            line-height: 1.25rem;
            text-align: center;
            margin-top: 1rem;
        }

        .signup-link a {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <form action="" method="post" class="form">
        <p class="form-title">Masuk</p>
        <div class="input-container">
            <input type="text" id="username" name="username" placeholder="Nama Pengguna" required>
        </div>
        <div class="input-container">
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="submit">Masuk</button>
        <p class="signup-link">
            Belum punya akun? <a href="registrasi_akun.php">Daftar</a>
        </p>

    </form>
</body>

</html>