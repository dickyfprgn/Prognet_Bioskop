<?php include 'koneksi.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Movies</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>

    <header class="header">
        <h1>Dashboard Admin - Movies</h1>
    </header>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Admin Menu</h2>
            <hr>
            <ul>
                <li><a href="index.php">Data User</a></li>
                <li><a href="tiket.php">Tiket</a></li>
                <li><a href="movie.php" class="active">Movie</a></li>
                <li><a href="studio.php">Studio</a></li>
                <li><a href="booking.php">Booking</a></li>
                <li><a href="booking_detail.php">Booking Detail</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="container">
            <h1>Data Movies</h1>
            <hr>

            <!-- Form Search dan Filter -->
            <form method="GET" class="filter-form">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <!-- Dropdown jumlah data -->
                    <div>
                        <label for="records_per_page">Tampilkan:</label>
                        <select name="records_per_page" id="records_per_page" onchange="this.form.submit()">
                            <option value="5" <?= isset($_GET['records_per_page']) && $_GET['records_per_page'] == '5' ? 'selected' : '' ?>>5</option>
                            <option value="10" <?= isset($_GET['records_per_page']) && $_GET['records_per_page'] == '10' ? 'selected' : '' ?>>10</option>
                        </select>
                        <label for="records_per_page">Baris</label>
                    </div>

                    <!-- Input Search -->
                    <div>
                        <input type="text" name="search" placeholder="Cari berdasarkan judul film" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                        <button type="submit">Cari</button>
                    </div>
                </div>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Poster</th>
                        <th>Judul</th>
                        <th>Genre</th>
                        <th>Durasi (menit)</th>
                        <th>Rating</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Default jumlah data per halaman
                    $records_per_page = isset($_GET['records_per_page']) ? (int)$_GET['records_per_page'] : 5;

                    // Halaman saat ini
                    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

                    // Offset untuk query
                    $offset = ($current_page - 1) * $records_per_page;

                    // Search filter
                    $search = isset($_GET['search']) ? $_GET['search'] : '';

                    // Query untuk menghitung total data
                    $total_data_query = "SELECT COUNT(*) AS total FROM tb_movie WHERE judul LIKE '%$search%'";
                    $total_data_result = $conn->query($total_data_query);
                    $total_data = $total_data_result->fetch_assoc()['total'];

                    // Query untuk mendapatkan data sesuai dengan limit, offset, dan filter search
                    $sql = "SELECT poster, judul, genre, durasi, rating FROM tb_movie WHERE judul LIKE '%$search%' LIMIT $records_per_page OFFSET $offset";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Pengecekan apakah poster adalah URL eksternal
                            $poster = $row['poster'];

                            // Jika poster adalah URL, tampilkan langsung dari URL
                            if (filter_var($poster, FILTER_VALIDATE_URL)) {
                                // Jika URL valid, langsung tampilkan gambar
                                echo "<tr>";
                                echo "<td><img src='" . $poster . "' alt='Poster''></td>";
                            } else {
                                // Jika bukan URL, anggap ini adalah path lokal (misalnya, path file di server)
                                // Konversi poster dari BLOB ke format base64 jika menggunakan file lokal
                                $poster = 'data:image/jpeg;base64,' . base64_encode($row['poster']);
                                echo "<tr>";
                                echo "<td><img src='" . $poster . "' alt='Poster''></td>";
                            }

                            // Menampilkan data lainnya
                            echo "<td>" . $row['judul'] . "</td>";
                            echo "<td>" . $row['genre'] . "</td>";
                            echo "<td>" . $row['durasi'] . "</td>";
                            echo "<td>" . $row['rating'] . "</td>";
                            echo "<td>
                                    <a href='edit_movie.php?judul=" . urlencode($row['judul']) . "' class='btn-edit'>Edit</a>
                                    <a href='delete_movie.php?judul=" . urlencode($row['judul']) . "' class='btn-delete' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Tidak ada data yang ditemukan.</td></tr>";
                    }

                    ?>
                </tbody>
            </table>

            <!-- Paginasi -->
            <div class="pagination">
                <?php
                $total_pages = ceil($total_data / $records_per_page);
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<a href='?page=$i&records_per_page=$records_per_page&search=$search' class='" . ($i == $current_page ? "active" : "") . "'>$i</a>";
                }
                ?>
            </div>
        </div>
    </div>

</body>

</html>