<?php
session_start();
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "pwd1");

// Login Admin (contoh sederhana)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil username dan password dari form login
    // Logika login admin akan ditambahkan di sini
}

// Cek login admin (tambahkan logika login di sini)

// Mengelola data pendaftaran
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        // Soft delete
        $id = $_GET['id'];
        $stmt = $conn->prepare("UPDATE registrasi SET is_delete = 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Peserta berhasil dihapus.</div>';
        } else {
            echo '<div class="alert alert-danger">Terjadi kesalahan saat menghapus peserta.</div>';
        }
        $stmt->close();
    }
}

// Menambahkan peserta seminar dari halaman manage registrasi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_participant'])) {
    $email = $_POST['email'];
    $nama = $_POST['nama'];
    $institusi = $_POST['institusi'];
    $country = $_POST['country'];
    $address = $_POST['address'];

    // Cek apakah email sudah terdaftar
    $checkEmail = $conn->prepare("SELECT * FROM registrasi WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        echo '<div class="alert alert-danger">Email sudah terdaftar!</div>';
    } else {
        // Simpan data ke database
        $stmt = $conn->prepare("INSERT INTO registrasi (email, nama, institusi, country, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $email, $nama, $institusi, $country, $address);
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Peserta berhasil ditambahkan!</div>';
        } else {
            echo '<div class="alert alert-danger">Terjadi kesalahan: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

$result = $conn->query("SELECT * FROM registrasi WHERE is_delete = 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container mt-5">
    <h2>Manage Registrasi</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Email</th>
                <th>Nama</th>
                <th>Institusi</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                <td><?php echo htmlspecialchars($row['institusi']); ?></td>
                <td>
                    <a href="?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>Tambah Peserta Baru</h3>

    <form method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama:</label>
            <input type="text" id="nama" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="institusi" class="form-label">Institusi:</label>
            <input type="text" id="institusi" name="institusi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="country" class="form-label">Country:</label>
            <input type="text" id="country" name="country" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address:</label>
            <textarea id="address" name="address" class="form-control" required></textarea>
        </div>

        <button type="submit" name="add_participant" class="btn btn-primary">Tambah Peserta</button>
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
