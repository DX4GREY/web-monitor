<?php
if (!empty($_POST)) {
    // Mendapatkan data yang dikirim melalui POST
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mengambil konten file JSON
    $jsonData = file_get_contents('./user/data-login.json');

    // Mendekodekan data JSON menjadi array asosiatif
    $users = json_decode($jsonData, true);

    // Memeriksa keberadaan username dan password yang cocok
    if (isset($users[md5($username)]) && $users[md5($username)] === md5($password)) {
        // Berhasil login
        $response = array(
            'status' => 'success',
            'message' => 'Login berhasil'
        );
    } else {
        // Gagal login
        $response = array(
            'status' => 'error',
            'message' => 'Perlu aktivasi'
            //'message' => 'Username atau password tidak sesuai'
        );
    }
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Tidak ada data yang di kirimkan'
    );
}

// Mengirimkan respon dalam format JSON
header('Content-type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
