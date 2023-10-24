<?php
// Memeriksa apakah cookie login tersimpan
if (!isset($_COOKIE['login_status']) || $_COOKIE['login_status'] !== 'true') {
    // Jika tidak ada cookie login atau login tidak valid, arahkan pengguna ke halaman login
    header('Location: ../../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Daftar Perangkat</title>
    <script src="../js/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="../css/sweetalert2.min.css">
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
    </style>
    <script src="./js/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="./css/sweetalert2.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container-center">
        <div class="container-bg">
            <div class="toolbar">
                <ul>
                    <li><a href="">menu1</a></li>
                    <li><a href="">menu2</a></li>
                </ul>
            </div>
            <div class="container">
                <h2>Ini Adalah sampel</h2>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Selamat Datang <b><?php echo $_COOKIE['username'] ?></b> (<a href="logout.php">Logout</a>)</p>
    </div>
    <script>

    </script>



</body>

</html>