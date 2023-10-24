<?php
if (isset($_GET['old']) & isset($_GET['new'])) {
    $filename = $_GET['old'];
    $fileNewName = $_GET['new'];
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $fileNewName . '"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize('../uploads/' . $filename));

    readfile('../uploads/' . $filename);
}else{
    echo 'No file';
}
