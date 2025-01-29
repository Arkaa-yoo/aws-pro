<?php
// Konfigurasi koneksi database
$host = ""; // Ganti dengan endpoint RDS
$dbname = ""; // Ganti dengan usernae db
$user = ""; // Ganti dengan username PostgreSQL Anda
$password = ""; // Ganti dengan password PostgreSQL Anda

// Variabel untuk pesan alert
$alertMessage = '';
$alertType = '';

try {
    // Membuat koneksi ke PostgreSQL
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Proses tombol beli
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_id'])) {
        $buy_id = intval($_POST['buy_id']); // Validasi data input sebagai integer

        // Ambil stok produk
        $query = "SELECT stok FROM barang WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $buy_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && $row['stok'] > 0) {
            // Update stok barang
            $new_stok = $row['stok'] - 1;
            $updateQuery = "UPDATE barang SET stok = :stok WHERE id = :id";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':stok', $new_stok, PDO::PARAM_INT);
            $updateStmt->bindParam(':id', $buy_id, PDO::PARAM_INT);

            if ($updateStmt->execute()) {
                $alertMessage = "Barang berhasil dibeli! Stok telah diperbarui.";
                $alertType = 'success';
            } else {
                $alertMessage = "Terjadi kesalahan saat memperbarui stok.";
                $alertType = 'error';
            }
        } else {
            $alertMessage = "Stok tidak mencukupi atau produk tidak ditemukan.";
            $alertType = 'warning';
        }
    }

    // Ambil data produk dari database
    $query = "SELECT * FROM barang ORDER BY id ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online - Produk Kami</title>
    <link rel="stylesheet" href="styles.css"> <!-- External CSS -->
</head>
<body>
    <!-- Alert -->
    <?php if (!empty($alertMessage)): ?>
        <div class="alert <?php echo htmlspecialchars($alertType); ?>">
            <p><?php echo htmlspecialchars($alertMessage); ?></p>
            <button class="close-alert" onclick="this.parentElement.style.display='none';">×</button>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <header>
        <div class="container">
            <h1>Toko Elektronik</h1>
            <nav>
                <ul>
                    <li><a href="#">Beranda</a></li>
                    <li><a href="#">Kategori</a></li>
                    <li><a href="#">Kontak</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="container">
            <h2>Produk Kami</h2>
            <div class="grid">
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_barang']); ?>" class="card-img">
                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($row['nama_barang']); ?></h3>
                            <p>Kategori: <?php echo htmlspecialchars($row['kategori']); ?></p>
                            <p>Harga: Rp <?php echo number_format($row['harga'], 2, ',', '.'); ?></p>
                            <p>Stok: <?php echo htmlspecialchars($row['stok']); ?></p>
                            <form method="POST">
                                <input type="hidden" name="buy_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit" class="btn">Beli</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2025 Toko Elektronik. Semua Hak Dilindungi.</p>
        </div>
    </footer>
</body>
</html>
