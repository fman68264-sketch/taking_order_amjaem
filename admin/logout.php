<?php
require_once '../config/koneksi.php';
unset($_SESSION['admin_id'], $_SESSION['admin_nama']);
session_destroy();
header('Location: ' . url('admin/login.php'));
exit;