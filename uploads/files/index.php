<?php
// Memeriksa apakah cookie login tersimpan
if (!isset($_COOKIE['login_status']) || $_COOKIE['login_status'] !== 'true') {
    // Jika tidak ada cookie login atau login tidak valid, arahkan pengguna ke halaman login
    header('Location: ../../login.php');
    exit();
}
// Mendapatkan daftar file di folder utama
$basePath = __DIR__;
$files = scandir($basePath);
$files = array_diff($files, array('.', '..'));

// Memfilter hanya file dengan ekstensi .json
$jsonFiles = array_filter($files, function ($file) {
    return pathinfo($file, PATHINFO_EXTENSION) === 'json';
});

/**
 * Fungsi rekursif untuk menghasilkan tampilan JSON berakar
 */

function generateKeys($json, $prefix = '')
{
    $result = array();
    foreach ($json as $key => $value) {
        $newKey = $key;
        $result[$newKey] = is_array($value) ? generateKeys($value, $newKey) : $value;
    }
    return $result;
}

function buildTree($keys)
{
    $html = '<ul class="tree">';
    foreach ($keys as $key => $value) {
        $html .= '<li>' . $key;

        if (!is_array($value)) {
            $html .= ' - ' . $value;
        }

        $html .= '</a>';

        if (is_array($value)) {
            $html .= buildTree($value);
        }

        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

function generateJsonTree($data)
{
    $keys = generateKeys($data);
    return buildTree($keys);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Daftar Perangkat</title>
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

        #response {
            margin-top: 20px;
            text-align: center;
            color: #333;
        }

        @media only screen and (max-width: 480px) {
            .container {
                max-width: 100%;
            }
        }

        h1 {
            margin-bottom: 20px;
            text-align: center;
        }

        .data ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .data li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .data li a {
            flex-grow: 1;
            color: #333;
            text-decoration: none;
            display: block;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .data li a:hover {
            background-color: #f5f5f5;
        }



        .active-file {
            background-color: #e3f2fd !important;
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
    <link rel="stylesheet" href="../../../css/sweetalert2.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container-center">
        <div class="container-bg">
            <div class="toolbar">
                <ul>
                    <li><a href="../../../uploads/files/image">Daftar Gambar</a></li>
                    <li><a href="../../../">Upload Data Perangkat</a></li>
                </ul>
            </div>
            <div class="container">
                <h2>Daftar Perangkat</h2>

                <?php if (empty($jsonFiles)) : ?>
                    <p>Tidak ada database di folder ini.</p>
                <?php else : ?>
                    <div class="data">
                        <ul>
                            <?php foreach ($jsonFiles as $file) : ?>
                                <?php $fileName = pathinfo($file, PATHINFO_FILENAME); ?>
                                <?php $fileUrl = '../../read?imei=' . urlencode($fileName); ?>
                                <li>
                                    <a href="<?php echo $fileUrl; ?>" <?php echo (isset($_GET['file']) && $_GET['file'] === $file) ? 'class="active-file"' : ''; ?>><?php echo $fileName; ?></a>
                                    <button class="delete-button" onclick="confirmDelete('<?php echo pathinfo($file, PATHINFO_FILENAME); ?>')">Hapus</button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <p>Perangkat yang sudah menginstall akan ditampilkan di halaman ini</p>
                    <p>Jika perangkat belum menginstall aplikasi bisa <a href="../../uploads/">Download Disini</a></p>
                <?php endif; ?>
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
                text: 'Apakah Anda yakin ingin menghapus perangkat "' + fileName + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteDatabase(fileName + '.json');
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
                            text: 'Perangkat "' + fileName + '" berhasil dihapus.',
                            icon: 'success'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Terjadi Kesalahan',
                            text: 'Terjadi kesalahan saat menghapus perangkat.',
                            icon: 'error'
                        });
                    }
                }
            };

            xhr.open('POST', './files/delete.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send('file=' + encodeURIComponent(fileName));
        }
    </script>



</body>

</html>