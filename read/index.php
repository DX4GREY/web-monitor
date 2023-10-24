<?php if (!empty($_GET['imei']) and !isset($_GET['raw'])) { ?>
    <?php
    // Memeriksa apakah cookie login tersimpan
    if (!isset($_COOKIE['login_status']) || $_COOKIE['login_status'] !== 'true') {
        // Jika tidak ada cookie login atau login tidak valid, arahkan pengguna ke halaman login
        header('Location: ../login.php');
        exit();
    }

    $fileName = $_GET["imei"] . '.json';
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
            $html .= '<li><b>' . $key . '</b>';

            if (!is_array($value)) {
                $html .= ' : ' . $value;
            }


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
    <html>

    <head>
        <title><?php echo 'IMEI ' . pathinfo($fileName, PATHINFO_FILENAME) ?></title>

        <script src="../js/sweetalert2.all.min.js"></script>
        <link rel="stylesheet" href="../css/sweetalert2.min.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                padding: 20px;
                max-width: max;
                margin: 0 auto;
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

            .json-content {
                background-color: #f5f5f5;
                border-radius: 5px;
                padding: 15px;
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

            .refresh-button {
                position: absolute;
                top: 10px;
                right: 10px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 5px;
                padding: 5px 10px;
                cursor: pointer;
            }

            .tree {
                list-style-type: none;
            }

            .tree ul {
                margin-top: 0;
                position: relative;
                padding-left: 1em;
            }

            .tree ul ul {
                margin-left: 1em;
            }

            .tree ul:before {
                content: "";
                display: block;
                width: 0;
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                border-left: 1px solid;
            }

            .tree li {
                margin: 0;
                padding: 0 1em;
                line-height: 1.2em;
                color: #000;
                position: relative;
            }


            .tree ul li:before {
                content: "";
                display: block;
                width: 10px;
                height: 0;
                border-top: 1px solid;
                margin-top: -1px;
                position: absolute;
                top: 1em;
                left: 0;
            }

            .tree ul li:last-child:before {
                height: auto;
                top: 1em;
                bottom: 0;
            }

            .indicator {
                margin-right: 5px;
            }

            .tree li a {
                text-decoration: none;
                color: #369;
            }

            .show-files {
                margin-left: 10px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 5px;
                padding: 5px 10px;
                cursor: pointer;
                width: max;
                height: 30px;
                font-size: medium;
            }
        </style>
    </head>

    <body>
        <div class="json-content">
            <?php
            // Membaca dan menampilkan konten file JSON yang dipilih

            if (isset($fileName)) {
                $selectedFile = $fileName;
                $selectedFilePath = '../uploads/files/' . $selectedFile;

                if (is_readable($selectedFilePath)) {
                    $jsonContent = file_get_contents($selectedFilePath);
                    $jsonData = json_decode($jsonContent, true);

                    if ($jsonData) {
                        echo "<h1>" . pathinfo($fileName, PATHINFO_FILENAME) . " : <a href=''>REFRESH DATA</a></h1><a href='" . $selectedFilePath . "'><button class='show-files'>Lihat File Asli</button></a>";
                        echo generateJsonTree($jsonData);
                    } else {
                        echo '<h1>Error</h1>';
                        echo '<p>Perangkat tidak valid.</p>';
                    }
                } else {
                    echo '<h1>Error</h1>';
                    echo '<p>Perangkat tidak ditemukan atau tidak dapat dibaca.</p>';
            ?>
                    <script>
                        Swal.fire({
                            title: 'Terjadi Kesalahan',
                            text: 'Harap input imei perangkat dengan benar!!',
                            icon: 'error'
                        });
                    </script>
            <?php
                }
            }
            ?>
        </div>
    </body>

    </html>
<?php
} else  if (isset($_GET['imei']) and isset($_GET["raw"])) {
    $fileName = $_GET["imei"];
    echo readfile('../uploads/files/' . $fileName . '.json');
} else {
?>
    <html>

    <head>
        <title>Belum ada input</title>
        <script src="../js/sweetalert2.all.min.js"></script>
        <link rel="stylesheet" href="../css/sweetalert2.min.css">
    </head>

    <body>
        <h1>Error</h1>
        <script>
            Swal.fire({
                title: 'Terjadi Kesalahan',
                text: 'Tidak ada input terdeteksi',
                icon: 'error'
            });
        </script>
    </body>

    </html>


<?php
}

?>