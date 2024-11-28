<?php
include 'koneksi.php';

// Proses penyimpanan data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_id = $_POST['movie_id'];
    $studio_id = $_POST['studio_id'];
    $tanggal = $_POST['tanggal'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $harga_tiket = $_POST['harga_tiket'];

    $sql = "INSERT INTO tb_schedule (movie_id, studio_id, tanggal, waktu_mulai, harga_tiket)
            VALUES ('$movie_id', '$studio_id', '$tanggal', '$waktu_mulai', '$harga_tiket')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Data berhasil ditambahkan!";
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal Tayang</title>
    <link rel="stylesheet" href="add.css">
</head>

<body>
    <header class="header">
        <h1>Tambah Jadwal Tayang</h1>
    </header>

    <div class="container">
        <a href="jadwal.php" class="btn-back">Kembali</a>

        <?php if (isset($success_message)): ?>
            <div class="success-message"><?= $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="error-message"><?= $error_message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div>
                <label for="movie_id">Film</label>
                <select name="movie_id" id="movie_id" required>
                    <option value="">Pilih Film</option>
                    <?php
                    $movies = $conn->query("SELECT movie_id, judul FROM tb_movie");
                    while ($movie = $movies->fetch_assoc()) {
                        echo "<option value='" . $movie['movie_id'] . "'>" . $movie['judul'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="studio_id">Studio</label>
                <select name="studio_id" id="studio_id" required>
                    <option value="">Pilih Studio</option>
                    <?php
                    $studios = $conn->query("SELECT studio_id, nama_studio FROM tb_studio");
                    while ($studio = $studios->fetch_assoc()) {
                        echo "<option value='" . $studio['studio_id'] . "'>" . $studio['nama_studio'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" required>
            </div>

            <div>
                <label for="waktu_mulai">Waktu Mulai</label>
                <input type="time" name="waktu_mulai" id="waktu_mulai" required>
            </div>

            <div>
                <label for="harga_tiket">Harga Tiket</label>
                <input type="text" name="harga_tiket" id="harga_tiket" required>
            </div>

            <button type="submit">Tambah Jadwal</button>
        </form>
    </div>
</body>

</html>