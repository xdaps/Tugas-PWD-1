<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Seminar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container mt-5">
    <h2>Pendaftaran Seminar</h2>

    <?php
    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "pwd1");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                echo '<div class="alert alert-success">Registrasi berhasil!</div>';
            } else {
                echo '<div class="alert alert-danger">Terjadi kesalahan: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        }
    }
    ?>

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

        <button type="submit" class="btn btn-primary">Daftar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>