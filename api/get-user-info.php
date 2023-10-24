<?php
// Mendapatkan nilai parameter "u" dari query string
$u = isset($_GET["u"]) ? $_GET["u"] : null;

// Mendapatkan isi file JSON
$jsonData = file_get_contents('./user/acc-info.json'); // Ganti dengan path yang sesuai

// Mendekodekan data JSON menjadi array asosiatif
$data = json_decode($jsonData, true);

// Memeriksa apakah data "awangdani" ada dalam array
if ($u !== null && isset($data[$u])) {
    // Data ditemukan
    $userData = $data[$u];

    // Mengirimkan respon dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($userData);
} else {
    // Data tidak ditemukan
    $response = array(
        'status' => 'error',
        'message' => 'Data not found'
    );

    // Mengirimkan respon dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
