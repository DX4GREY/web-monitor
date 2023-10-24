<?php
$fileDownload = '';
$fileNamed = '';
$filePostVar = isset($_GET['file']);
if ($filePostVar) {
    $fileDownload = $_GET['file'];

    $underscoreIndex = strpos($fileDownload, '_');
    if ($underscoreIndex !== false) {
        $newFilename = substr($fileDownload, $underscoreIndex + 1);
    }
}

?>

<html>

<head>
    <title><?php echo $newFilename ?></title>
    <script src="../../js/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="../../css/sweetalert2.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #222;
            color: #fff;
            font-family: Arial, sans-serif;
            padding: 20px;
            text-align: center;
            display: flex;
            align-items: center;
            align-content: center;
        }

        h1 {
            color: #ccc;
        }

        .container {
            max-width: max;
            margin: 0 auto;
            background-color: #333;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        .download-button {
            margin-top: 20px;
        }

        .file-details {
            margin-top: 20px;
            background-color: #444;
            border-radius: 5px;
            padding: 20px;
            text-align: left;
            width: 300px;
            max-width: 90%;
        }

        .file-details div {
            margin-bottom: 10px;
        }

        .file-details span {
            font-weight: bold;
        }

        .download-button a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #666;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .download-button a:hover {
            background-color: #999;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?php echo $newFilename; ?></h1>
        <?php if ($filePostVar) { ?>
            <div class="download-button">
                <a href="./download/down.php?old=<?php echo $fileDownload ?>&new=<?php echo $newFilename ?>">
                    Download <b><?php echo $newFilename ?></b>
                </a>
            </div>
            <div class="file-details">
                <div><span>Nama File:</span> <?php echo $newFilename ?></div>
                <div><span>Ukuran File:</span> <?php echo formatBytes(filesize('../uploads/' . $fileDownload)) ?></div>
                <div><span>Ekstensi File:</span> <?php echo pathinfo($newFilename, PATHINFO_EXTENSION) ?></div>
            </div>
        <?php } else { ?>
            <p>404 Forbidden</p>
        <?php } ?>
    </div>

    <?php
    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    ?>
</body>

</html>