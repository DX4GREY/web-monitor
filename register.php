<?php
// Memeriksa apakah cookie login tersimpan
if (isset($_COOKIE['login_status']) && $_COOKIE['login_status'] === 'true') {
    // Langsung arahkan pengguna ke halaman yang diinginkan
    header('Location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Register Admin Account</title>
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="./js/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/sweetalert2.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #f8f8f8;
            font-family: Arial, sans-serif;
            padding-top: 100px;
        }

        .container {
            width: 80%;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            text-align: center;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#loginForm').submit(function(e) {
                e.preventDefault();

                // Mengambil nilai username dan password dari form
                var user = $('#username').val();
                var pass = $('#password').val();
                var repPass = $('#repeat-password').val();
                //AWANGFARAH130307110806
                var keyval = $('#key-validator').val();
                $.ajax({
                    url: './api/register.php', // Ganti dengan URL API login yang sesuai
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        username: user,
                        password: pass,
                        repeat_password: repPass,
                        key_validator: keyval

                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            // Registrasi berhasil
                            Swal.fire({
                                icon: 'success',
                                title: 'Registrasi berhasil',
                                text: response.message
                            }).then((result) => {
                                // Lakukan tindakan setelah registrasi berhasil, misalnya:
                                // - Redirect ke halaman login
                                // - Menampilkan pesan sukses
                                window.location.href = './login.php'; // Ganti dengan URL halaman login yang sesuai
                            });
                        } else {
                            // Registrasi gagal
                            Swal.fire({
                                icon: 'error',
                                title: 'Registrasi gagal',
                                text: response.message
                            });
                        }
                    },

                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });

            });
        });
    </script>
</head>

<body>
    <div class="container">
        <h2>Register Admin</h2>
        <form id="loginForm">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="repeat-password">Repeat Password:</label>
                <input type="password" id="repeat-password" name="repeat-password" required>
            </div>
            <div class="form-group">
                <label for="key-validator">Key Validator:</label>
                <input type="password" id="key-validator" name="key-validator" required>
            </div>

            <button type="submit" class="btn">Register Admin Account</button>
            <p>Sudah ada akun admin? <a href="./login.php"><i><b>Login</b></i></a></p>
            <p>Jika anda belum membaca aturan bisa buka <b><a href="./rules.html">Disini</a></b></p>
        </form>
    </div>
</body>

</html>