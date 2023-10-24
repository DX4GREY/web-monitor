<?php
if (!empty($_POST)) {
    // Mendapatkan data yang dikirim melalui POST
    $username = $_POST['username'];
    $password = $_POST['password'];
    $repPass = $_POST['repeat_password'];
    $key = $_POST['key_validator'];

    // Mengambil konten file JSON
    $jsonData = file_get_contents('./user/data-login.json');

    // Mendekodekan data JSON menjadi array asosiatif
    $users = json_decode($jsonData, true);

    // Memeriksa apakah username sudah ada dalam file JSON
    if ($repPass == $password) {
        if (isset($users[md5($username)])) {
            // Username sudah terdaftar
            $response = array(
                'status' => 'error',
                'message' => 'Username sudah ada'
            );
        } else {
            if ($key != 'AWANGFARAH130307110806') {
                $response = array(
                    'status' => 'error',
                    'message' => 'Kunci validator tidak valid'
                );
            } else {

                // Menambahkan username dan password baru ke array
                $users[md5($username)] = md5($password);

                // Mengubah array menjadi data JSON
                $jsonData = json_encode($users);

                // Menulis kembali data JSON ke file
                file_put_contents('./user/data-login.json', $jsonData);

                // Registrasi berhasil
                $response = array(
                    'status' => 'success',
                    'message' => 'Registration successful!, harap login terlebih dahulu'
                );
            }
        }
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Harap ulangi password dengan benar'
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
