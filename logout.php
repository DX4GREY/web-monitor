<?php
// Hapus cookie login
setcookie('login_status', '', time() - 3600, '/');

// Arahkan pengguna ke halaman login
header('Location: login.php');
exit();
?>
