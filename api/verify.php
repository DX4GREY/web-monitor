<?php

if (isset($_POST['verificationCode'])) {
    // Menerima kode undangan dari permintaan
    $verificationCode = $_POST['verificationCode'];

    // Kode undangan yang valid
    $validVerificationCode = "131103080706";

    // Memeriksa apakah kode undangan yang diberikan sesuai
    if ($verificationCode === $validVerificationCode) {
        $response = array('status' => 'success', 'message' => 'Kode undangan valid');
    } else {
        $response = array('status' => 'error', 'message' => 'Kode undangan tidak valid');
    }
} else {
    $response = array('status' => 'error', 'message' => 'Kode tidak ada');
}

// Mengirimkan respons sebagai JSON
header('Content-Type: application/json');
echo json_encode($response);
