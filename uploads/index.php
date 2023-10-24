<?php
// Memeriksa apakah cookie login tersimpan
if (!isset($_COOKIE['login_status']) || $_COOKIE['login_status'] !== 'true') {
    // Jika tidak ada cookie login atau login tidak valid, arahkan pengguna ke halaman login
    header('Location: ../../login.php');
    exit();
}
?>
<?php
// Mendapatkan daftar file di folder utama
$basePath = __DIR__;
$files = scandir($basePath);
$files = array_diff($files, array('.', '..'));

// Memfilter hanya file dengan ekstensi gambar (jpg, jpeg, png, gif)
$imageExtensions = ['apk', 'zip', 'tar', 'rar', '7z'];
$imageFiles = array_filter($files, function ($file) use ($imageExtensions) {
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($extension, $imageExtensions);
});

?>
<!DOCTYPE html>
<html>

<head>
    <title>Daftar File</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
            height: 100%;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        .toolbar {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .toolbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .toolbar ul li {
            display: inline-block;
            margin-right: 10px;
        }

        .toolbar ul li a {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .toolbar ul li a:hover {
            background-color: #555;
        }

        .container-bg {
            position: relative;
            margin: 0 auto;
            justify-content: center;
            max-width: max;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        .container-center {
            height: 100%;
            width: 100%;
            align-items: center;
            display: flex;
            justify-content: center;
        }


        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            margin: 0;

            justify-content: center;
            text-align: center;
        }

        .container {
            max-width: max;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        .list ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .list li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .list li a {
            flex-grow: 1;
            color: #333;
            text-decoration: none;
            display: block;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            position: relative;
        }

        .list li a:before {
            content: "";
            position: absolute;
            top: 50%;
            left: -12px;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background-color: black;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .list li a.selected-item:before {
            opacity: 1;
        }

        .list li a:hover {
            background-color: #f5f5f5;
        }

        .image-dialog {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 9999;
        }

        .image-dialog-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
        }

        .image-dialog img {
            max-width: 100%;
            max-height: 100%;
            display: block;
            margin: 0 auto;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px;
            color: #fff;
            font-size: 14px;
            background-color: rgba(0, 0, 0, 0.5);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            z-index: 9999;
        }

        .progress-bar {
            width: 100%;
            height: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-bar-inner {
            height: 100%;
            background-color: #4caf50;
            width: 0;
            transition: width 0.3s;
        }

        .delete-button {
            margin-left: 10px;
            background-color: #ff0000;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
    <script src="../../../js/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href=".../../../../css/sweetalert2.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container-center">
        <div class="container-bg">
            <div class="toolbar">
                <ul>
                    <li><a href="../../../">Menu</a></li>
                </ul>
            </div>
            <div class="container">
                <h2>Daftar File</h2>
                <div class="list">
                    <?php if (empty($imageFiles)) : ?>
                        <p>Tidak ada file disini.</p>
                    <?php else : ?>
                        <ul>
                            <?php foreach ($imageFiles as $file) : ?>
                                <?php $fileName = pathinfo($file, PATHINFO_FILENAME); ?>
                                <?php $fileUrl = '../../../download?file=' . urlencode($file); ?>
                                <?php
                                $underscoreIndex = strpos($file, '_');
                                if ($underscoreIndex !== false) {
                                    $newFilename = substr($file, $underscoreIndex + 1);
                                }
                                ?>
                                <li>
                                    <a href="<?php echo $fileUrl; ?>" class="<?php echo $selectedFile === $file ? 'selected-item' : ''; ?>"><?php echo $newFilename; ?></a>
                                    <button class="delete-button" onclick="confirmDelete('<?php echo $file; ?>')">Hapus</button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                </div>
                <p>Hanya file dengan ekstensi apk, zip, tar, 7z, dan rar yang akan ditampilkan di halaman ini</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Selamat Datang <b><?php echo $_COOKIE['username'] ?></b> (<a href="../../../logout.php">Logout</a>)</p>
    </div>
    <script>
        function confirmDelete(fileName) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Apakah Anda yakin ingin menghapus file ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteDatabase(fileName);
                }
            });
        }

        function deleteDatabase(fileName) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: 'file berhasil dihapus.',
                            icon: 'success'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Terjadi Kesalahan',
                            text: 'Terjadi kesalahan saat menghapus file.',
                            icon: 'error'
                        });
                    }
                }
            };

            xhr.open('POST', '../../uploads/delete.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send('file=' + encodeURIComponent(fileName));
        }
    </script>
</body>

</html>