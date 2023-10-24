<?php
// Memeriksa apakah cookie login tersimpan
if (!isset($_COOKIE['login_status']) || $_COOKIE['login_status'] !== 'true') {
    // Jika tidak ada cookie login atau login tidak valid, arahkan pengguna ke halaman login
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Upload File Database</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            align-content: center;
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
            max-width: max-content;
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
            max-width: max-content;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input[type="file"] {
            display: none;
        }

        .form-group .file-input-label {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
        }

        .form-group .file-input-label:hover {
            background-color: #2980b9;
        }

        .form-group .file-name {
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
            color: #666;
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #2ecc71;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #27ae60;
        }

        #response {
            margin-top: 20px;
            text-align: center;
            color: #333;
        }

        #response.success {
            color: #2ecc71;
        }

        #response.error {
            color: #e74c3c;
        }

        @media only screen and (max-width: 480px) {
            .container {
                max-width: 100%;
            }
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #f1f1f1;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 10px;
        }

        .progress {
            width: 0;
            height: 100%;
            background-color: #3498db;
            transition: width 0.3s ease-in-out;
        }

        .cancel-button {
            margin-left: 10px;
            background-color: #ff0000;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            margin-top: 10px;
        }

        .progress-layout {
            align-items: center;
            justify-content: center;
            text-align: center;
            display: none;
        }
    </style>
    <script src="js/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="css/sweetalert2.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container-center">
        <div class="container-bg">
            <div class="toolbar">
                <ul>
                    <li><a href="./uploads/files/image">Daftar Gambar</a></li>
                    <li><a href="./uploads/files">Daftar Perangkat</a></li>
                </ul>
            </div>
            <div class="container">
                <h2>Upload Data Perangkat</h2>

                <div class="form-group">
                    <label for="fileInput">Pilih File</label>
                    <input type="file" name="file" id="fileInput">
                    <label class="file-input-label" for="fileInput">Pilih File</label>
                    <span class="file-name">Silahkan pilih file terlebih dahulu</span>
                </div>
                <div class="form-group">
                    <button type="button" id="upload-button" onclick="uploadFile()">Upload</button>
                </div>
                <p>Hanya file dengan ekstensi .json, .jpg, .jpeg, .png, atau .gif yang diperbolehkan.<br>Untuk info lebih lanjutnya bisa baca <a href="./rules.html">Aturan</a></p>
                <div class="progress-layout">
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                    <button class="cancel-button" onclick="abortXhr()">BATALKAN</button>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Selamat Datang <b><?php echo $_COOKIE['username'] ?></b> (<a href="logout.php">Logout</a>)</p>
    </div>

    <script>
        var upButton = document.getElementById('upload-button');
        var fileInput = document.getElementById('fileInput');
        var fileNameSpan = document.querySelector('.file-name');
        var progressBar = document.querySelector('.progress');
        var progressBarCon = document.querySelector('.progress-layout');

        var xhr = new XMLHttpRequest();
        <?php if (!isset($_COOKIE['info'])) { ?>
            Swal.fire({
                icon: 'warning',
                title: 'Pilih File',
                text: 'Pilih file json yang valid untuk memvalidasi perangkat, dan jika ingin mengunggah file gambar pastikan formatnya memenuhi apa yang di dukung, klik lihat aturan di bawah',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Lihat Aturan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'rules.html';
                    document.cookie = "info=true;";
                }
            });

        <?php } ?>

        fileInput.addEventListener('change', function() {
            fileNameSpan.textContent = fileInput.files[0].name;
        });
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        fileInput.addEventListener('change', function() {
            fileNameSpan.textContent = fileInput.files[0].name;
        });

        function abortXhr() {
            Toast.fire({
                icon: 'success',
                title: 'Proses upload file di batalkan',
            });
            xhr.abort();
            progressBar.style.width = '0%';
            progressBarCon.style.display = "none";
            upButton.disabled = false;
        }

        function uploadFile() {
            upButton.disabled = true;

            var file = fileInput.files[0];

            var formData = new FormData();
            formData.append('file', file);

            xhr.open('POST', 'upload.php', true);

            // Tambahkan event listener untuk memantau progress pengunggahan
            xhr.upload.addEventListener('progress', function(event) {
                if (event.lengthComputable) {
                    var percentComplete = (event.loaded / event.total) * 100;
                    progressBar.style.width = percentComplete + '%';
                }
            });

            xhr.onloadstart = function() {
                progressBarCon.style.display = "block";
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);

                    if (response.status === 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'File berhasil diunggah',
                            text: 'URL file: ' + response.fileUrl,
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan',
                            text: response.message,
                        });
                    }
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan',
                        text: 'Terjadi kesalahan saat menghubungi server.',
                    });
                }

                progressBar.style.width = '0%';
                progressBarCon.style.display = "none";
                upButton.disabled = false;
            };



            xhr.send(formData);
        }
    </script>
</body>

</html>