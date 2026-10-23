<?php
require_once '../config/koneksi.php';
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $koneksi->prepare("SELECT * FROM admin WHERE username=?");
$stmt->bind_param('s', $username); $stmt->execute();
$admin = $stmt->get_result()->fetch_assoc(); $stmt->close();

if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id']   = $admin['id'];
    $_SESSION['admin_nama'] = $admin['nama_lengkap'];
    header('Location: ' . url('admin/dashboard.php'));
} else {
    $_SESSION['error'] = 'Username atau password salah.';
    header('Location: ' . url('admin/login.php'));
}