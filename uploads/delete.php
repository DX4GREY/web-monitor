<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['file'])) {
        $fileName = $_POST['file'];
        $basePath = __DIR__;
        $filePath = $basePath . '/' . $fileName;

        if (file_exists($filePath) && is_writable($filePath)) {
            unlink($filePath);
            echo 'success';
        } else {
            echo 'error';
        }
    }
}
