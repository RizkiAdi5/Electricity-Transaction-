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


$alertMessage = '';

if (isset($_POST['create'])) {

    $pelanggan_id = isset($_POST['pelanggan_id']) ? $_POST['pelanggan_id'] : null;
    $bulan = isset($_POST['bulan']) ? $_POST['bulan'] : null;
    $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : null;
    $daya = isset($_POST['daya']) ? $_POST['daya'] : null;
    $pemakaian_kwh = isset($_POST['pemakaian_kwh']) ? $_POST['pemakaian_kwh'] : null;
    $nomor_meter = isset($_POST['nomor_meter']) ? $_POST['nomor_meter'] : null;
    $total_dibayarkan = isset($_POST['total_dibayarkan']) ? floatval($_POST['total_dibayarkan']) : null;
    $metode_pembayaran = isset($_POST['metode_pembayaran']) ? $_POST['metode_pembayaran'] : null;


    if ($pelanggan_id && $bulan && $tahun && $daya && $pemakaian_kwh && $nomor_meter && $total_dibayarkan && $metode_pembayaran) {
        $sql = "INSERT INTO penggunaan (pelanggan_id, bulan, tahun, daya, pemakaian_kwh, nomor_meter, total_dibayarkan, metode_pembayaran) 
                VALUES (:pelanggan_id, :bulan, :tahun, :daya, :pemakaian_kwh, :nomor_meter, :total_dibayarkan, :metode_pembayaran)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':pelanggan_id', $pelanggan_id, PDO::PARAM_INT);
        $stmt->bindParam(':bulan', $bulan, PDO::PARAM_STR);
        $stmt->bindParam(':tahun', $tahun, PDO::PARAM_STR);
        $stmt->bindParam(':daya', $daya, PDO::PARAM_INT);
        $stmt->bindParam(':pemakaian_kwh', $pemakaian_kwh, PDO::PARAM_INT);
        $stmt->bindParam(':nomor_meter', $nomor_meter, PDO::PARAM_STR);
        $stmt->bindParam(':total_dibayarkan', $total_dibayarkan, PDO::PARAM_STR);
        $stmt->bindParam(':metode_pembayaran', $metode_pembayaran, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Menampilkan alert dengan rekapan besaran
            $alertMessage .= "<strong>Data penggunaan berhasil disimpan!</strong><br>";
            $alertMessage .= "Pelanggan: $pelanggan_id<br>";
            $alertMessage .= "Bulan: $bulan $tahun<br>";
            $alertMessage .= "Daya: $daya VA<br>";
            $alertMessage .= "Pemakaian KWh: $pemakaian_kwh<br>";
            $alertMessage .= "Total dibayarkan: Rp $total_dibayarkan<br>";
            $alertMessage .= "Metode pembayaran: $metode_pembayaran<br>";

            // Menampilkan form untuk input status pembayaran
            $alertMessage .= '<form action="inputdata.php" method="post">';
            $alertMessage .= '<input type="hidden" name="token" value="1">';
            $alertMessage .= '<input type="hidden" name="pelanggan_id" value="' . htmlspecialchars($pelanggan_id) . '">';
            $alertMessage .= '<input type="hidden" name="bulan" value="' . htmlspecialchars($bulan) . '">';
            $alertMessage .= '<input type="hidden" name="tahun" value="' . htmlspecialchars($tahun) . '">';
            $alertMessage .= '<div class="form-group">';
            $alertMessage .= '<label for="status_pembayaran">Status Pembayaran:</label>';
            $alertMessage .= '<select class="form-control" id="status_pembayaran" name="status_pembayaran" required>';
            $alertMessage .= '<option value="">Pilih Status Pembayaran</option>';
            $alertMessage .= '<option value="Lunas">Lunas</option>';
            $alertMessage .= '<option value="Belum Dibayar">Belum Dibayar</option>';
            $alertMessage .= '</select>';
            $alertMessage .= '</div>';
            $alertMessage .= '<button type="submit" class="btn btn-primary">Simpan</button>';
            $alertMessage .= '</form>';
        } else {
            $alertMessage = "Error: Could not execute the query";
        }
    } else {
        $alertMessage = "Error: Please fill out all required fields";
    }
}

// Proses untuk menyimpan status pembayaran dan menampilkan nomor token jika sudah lunas
if (isset($_POST['token'])) {
    $status_pembayaran = isset($_POST['status_pembayaran']) ? $_POST['status_pembayaran'] : null;
    $pelanggan_id = isset($_POST['pelanggan_id']) ? $_POST['pelanggan_id'] : null;
    $bulan = isset($_POST['bulan']) ? $_POST['bulan'] : null;
    $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : null;
    $pelanggan_id = isset($_POST['pelanggan_id']) ? $_POST['pelanggan_id'] : null;

    $daya = isset($_POST['daya']) ? $_POST['daya'] : null;
    $pemakaian_kwh = isset($_POST['pemakaian_kwh']) ? $_POST['pemakaian_kwh'] : null;
    $nomor_meter = isset($_POST['nomor_meter']) ? $_POST['nomor_meter'] : null;
    $total_dibayarkan = isset($_POST['total_dibayarkan']) ? floatval($_POST['total_dibayarkan']) : null;
    $metode_pembayaran = isset($_POST['metode_pembayaran']) ? $_POST['metode_pembayaran'] : null;

    if ($status_pembayaran && $pelanggan_id && $bulan && $tahun) {
        // Query untuk menyimpan status pembayaran dan nomor token
        $sqlUpdate = "UPDATE penggunaan SET status_pembayaran = :status_pembayaran, nomor_token = :nomor_token";
        $sqlUpdate .= " WHERE pelanggan_id = :pelanggan_id AND bulan = :bulan AND tahun = :tahun";

        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':status_pembayaran', $status_pembayaran, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':nomor_token', $nomor_token, PDO::PARAM_STR); // Bind nomor_token
        $stmtUpdate->bindParam(':pelanggan_id', $pelanggan_id, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':bulan', $bulan, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':tahun', $tahun, PDO::PARAM_STR);

        if ($status_pembayaran === 'Lunas') {
            // Jika status pembayaran lunas, generate nomor token
            $nomor_token = generateRandomToken();
        } else {
            $nomor_token = null;
        }

        if ($stmtUpdate->execute()) {
            if ($status_pembayaran === 'Lunas') {
                $alertMessage .= "Pelanggan: $pelanggan_id<br>";
                $alertMessage .= "Bulan: $bulan $tahun<br>";
                $alertMessage .= "Daya: $daya VA<br>";
                $alertMessage .= "Pemakaian KWh: $pemakaian_kwh<br>";
                $alertMessage .= "Total dibayarkan: Rp $total_dibayarkan<br>";
                $alertMessage .= "Metode pembayaran: $metode_pembayaran<br>";
                $alertMessage .= "<br><strong>Status Pembayaran:</strong> $status_pembayaran<br>";
                $alertMessage .= "<strong>Nomor Token:</strong> $nomor_token";
            } else {
                $alertMessage .= "<br><strong>Status Pembayaran:</strong> $status_pembayaran";
            }
        } else {
            $alertMessage = "Error: Tidak bisa menambahkan pembyaran";
        }
    } else {
        $alertMessage = "Error : Masukkan seluruh data yang diperlukan";
    }
}

// Fungsi untuk menghasilkan nomor token acak sepanjang 20 digit
function generateRandomToken()
{
    $token = '';
    for ($i = 0; $i < 20; $i++) {
        $token .= mt_rand(0, 9);
    }
    return $token;
}

$sql = "SELECT pelanggan.id, pelanggan.user_id, pelanggan.address, user.name AS user_name 
        FROM pelanggan 
        LEFT JOIN user ON pelanggan.user_id = user.id";
$pelangganQuery = $pdo->query($sql);
$pelangganResults = $pelangganQuery->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Penggunaan Pelanggan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function limitNomorMeter() {
            var nomorMeterInput = document.getElementById("nomor_meter");
            var value = nomorMeterInput.value.trim();
            if (value.length < 11) {
                nomorMeterInput.setCustomValidity("Nomor meter harus minimal 11 karakter.");
            } else if (value.length > 12) {
                nomorMeterInput.setCustomValidity("Nomor meter tidak boleh lebih dari 12 karakter.");
            } else {
                nomorMeterInput.setCustomValidity("");
            }
        }
    </script>
    <style>
        .input-group-text {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Input Data Penggunaan Pelanggan</h2>
        <?php if (!empty($alertMessage)) : ?>
            <div class="alert alert-success" role="alert">
                <?= $alertMessage ?>
            </div>
        <?php endif; ?>
        <form action="inputdata.php" method="post">
            <input type="hidden" name="create" value="1">
            <div class="form-group">
    <label for="pelanggan_id">Pelanggan:</label>
    <select class="form-control" id="pelanggan_id" name="pelanggan_id" required>
        <option value="">Select Pelanggan</option>
        <?php foreach ($pelangganResults as $pelanggan) : ?>
            <option value="<?= htmlspecialchars($pelanggan['id']) ?>">
                <?= htmlspecialchars($pelanggan['user_name'] . ' - ' . $pelanggan['address']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
            <div class="form-group">
                <label for="bulan">Bulan:</label>
                <select id="bulan" name="bulan" class="form-control">
                    <option value="Januari">Januari</option>
                    <option value="Februari">Februari</option>
                    <option value="Maret">Maret</option>
                    <option value="April">April</option>
                    <option value="Mei">Mei</option>
                    <option value="Juni">Juni</option>
                    <option value="Juli">Juli</option>
                    <option value="Agustus">Agustus</option>
                    <option value="September">September</option>
                    <option value="Oktober">Oktober</option>
                    <option value="November">November</option>
                    <option value="Desember">Desember</option>
                </select>

            </div>
            <div class="form-group">
                <label for="tahun">Tahun:</label>
                <select class="form-control" id="tahun" name="tahun" required>
                    <option value="">Pilih Tahun</option>
                    <?php
                    $tahun_sekarang = date("Y");
                    for ($tahun = $tahun_sekarang; $tahun >= $tahun_sekarang - 10; $tahun--) {
                        echo "<option value=\"$tahun\">$tahun</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nomor_meter">Nomor meter:</label>
                <input type="text" class="form-control" id="nomor_meter" name="nomor_meter" required maxlength="12" oninput="limitNomorMeter()">
            </div>
            <div class="form-group">
                <label for="daya">Daya:</label>
                <select class="form-control" id="daya" name="daya" required onchange="toggleCustomInput(this)">
                    <option value="" disabled selected>Pilih daya</option>
                    <option value="450">450 VA</option>
                    <option value="900">900 VA</option>
                    <option value="1300">1300 VA</option>
                    <option value="2200">2200 VA</option>
                    <option value="3500">3500 VA</option>
                    <option value="4400">4400 VA</option>
                    <option value="5500">5500 VA</option>
                    <option value="6600">6600 VA</option>
                    <option value="7700">7700 VA</option>
                    <option value="10600">10600 VA</option>
                    <option value="11000">11000 VA</option>
                    <option value="13000">13000 VA</option>
                    <option value="16500">16500 VA</option>
                    <option value="23000">23000 VA</option>
                    <option value="custom">Lainnya</option>
                </select>
            </div>

            <div class="form-group" id="customDayaContainer" style="display: none;">
                <label for="customDaya">Masukkan Daya:</label>
                <input type="number" class="form-control" id="customDaya" name="customDaya">
            </div>

            <script>
                function toggleCustomInput(select) {
                    var customDayaContainer = document.getElementById('customDayaContainer');
                    var customDayaInput = document.getElementById('customDaya');

                    if (select.value === 'custom') {
                        customDayaContainer.style.display = 'block';
                        customDayaInput.setAttribute('required', 'required');
                    } else {
                        customDayaContainer.style.display = 'none';
                        customDayaInput.removeAttribute('required');
                    }
                }
            </script>

            <div class="form-group">
                <label for="pemakaian_kwh">Pemakaian KWh:</label>
                <select class="form-control" id="pemakaian_kwh" name="pemakaian_kwh" required onchange="toggleCustomKwhInput(this)">
                    <option value="" disabled selected>Pilih pemakaian kWh</option>
                    <option value="50">50 kWh</option>
                    <option value="100">100 kWh</option>
                    <option value="150">150 kWh</option>
                    <option value="200">200 kWh</option>
                    <option value="250">250 kWh</option>
                    <option value="300">300 kWh</option>
                    <option value="350">350 kWh</option>
                    <option value="400">400 kWh</option>
                    <option value="450">450 kWh</option>
                    <option value="500">500 kWh</option>
                    <option value="custom">Lainnya</option>
                </select>
            </div>

            <div class="form-group" id="customKwhContainer" style="display: none;">
                <label for="customKwh">Masukkan Pemakaian KWh:</label>
                <input type="number" class="form-control" id="customKwh" name="customKwh" min="0" step="0.01">
            </div>

            <script>
                function toggleCustomKwhInput(select) {
                    var customKwhContainer = document.getElementById('customKwhContainer');
                    var customKwhInput = document.getElementById('customKwh');

                    if (select.value === 'custom') {
                        customKwhContainer.style.display = 'block';
                        customKwhInput.setAttribute('required', 'required');
                    } else {
                        customKwhContainer.style.display = 'none';
                        customKwhInput.removeAttribute('required');
                    }
                }
            </script>

            <div class="form-group">
                <label for="total_dibayarkan">Total dibayarkan:</label>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="text" class="form-control" id="total_dibayarkan" name="total_dibayarkan" required oninput="formatCurrency(this)">
                </div>
            </div>
            <div class="input-group">
                <select class="form-control" id="metode_pembayaran" name="metode_pembayaran" onchange="toggleCustomOption()">
                    <option value="" disabled selected>Pilih metode pembayaran</option>
                    <option value="transfer">Transfer Bank</option>
                    <option value="cash">Cash</option>
                    <option value="credit_card">Kartu Kredit</option>
                    <option value="other">Lainnya</option>
                </select>
                <input type="text" class="form-control custom-option" id="custom_metode_pembayaran" name="custom_metode_pembayaran" placeholder="Masukkan metode pembayaran lain jika diperlukan">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Beranda</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>