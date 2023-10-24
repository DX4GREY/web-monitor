<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
function getCurrentTime()
{
    $currentTime = date('YmdHis');
    return $currentTime;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        // Mendapatkan informasi file
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];

        // Memeriksa apakah tidak ada kesalahan saat mengunggah file
        if ($fileError === UPLOAD_ERR_OK) {
            // Memeriksa apakah file memiliki ekstensi .json
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if ($fileExtension === 'json') {
                // Tentukan folder tujuan penyimpanan file yang diunggah
                $uploadDir = 'uploads/files/';
                $filePath = $uploadDir . $fileName;
                // Pindahkan file ke folder tujuan
                if (move_uploaded_file($fileTmpName, $filePath)) {
                    try {
                        $response['status'] = 'success';
                        $response['message'] = 'Data perangkat berhasil diunggah.';
                        $response['fileUrl'] = $filePath;
                        $jsonString = json_encode(json_decode(file_get_contents($filePath)), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                        $result = file_put_contents($filePath, $jsonString);
                    } catch (Exception $e) {
                        $response['status'] = 'error';
                        $response['message'] = 'JSON Tidak valid';
                        $response['fileUrl'] = $filePath;
                        unlink($filePath);
                    }
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Terjadi kesalahan saat mengunggah data perangkat.';
                }
            } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                // Tentukan folder tujuan penyimpanan file foto yang diunggah
                $uploadDir = 'uploads/files/image/';
                $filePath = $uploadDir . $fileName;

                // Pindahkan file foto ke folder tujuan
                if (move_uploaded_file($fileTmpName, $filePath)) {
                    $response['status'] = 'success';
                    $response['message'] = 'Foto berhasil diunggah.';
                    $response['fileUrl'] = $filePath;
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Terjadi kesalahan saat mengunggah foto.';
                }
            } else if (in_array($fileExtension, ['apk', 'zip', 'tar', 'rar', '7z'])) {
                $uploadDir = 'uploads/';
                $filePath = $uploadDir . getCurrentTime() . '_' . $fileName;

                if (move_uploaded_file($fileTmpName, $filePath)) {
                    $response['status'] = 'success';
                    $response['message'] = 'File berhasil diunggah.';
                    $response['fileUrl'] = $filePath;
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Terjadi kesalahan saat mengunggah file.';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Format file tidak valid. Hanya file dengan ekstensi .json, .jpg, .jpeg, .png, atau .gif yang diperbolehkan.';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Terjadi kesalahan: ' . $fileError;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Tidak ada file yang diunggah.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Metode yang diperbolehkan hanya POST.';
}

header('Content-type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
