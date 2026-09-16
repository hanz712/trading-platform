<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/../../config/database.php';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $raw_password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($email) && !empty($raw_password)) {
        $password_hash = password_hash($raw_password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'student')");
            $stmt->execute([$username, $email, $password_hash]);
            $success = "Registrasi berhasil! Silakan login.";
        } catch (PDOException $e) {
            $error = "Email sudah terdaftar atau terjadi kesalahan database.";
        }
    } else {
        $error = "Semua kolom wajib diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Trading Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white flex items-center justify-center min-h-screen px-4">
    <div class="bg-slate-800 p-8 rounded-2xl shadow-xl w-full max-w-md border border-slate-700">
        <div class="mb-6">
            <a href="/" class="text-sm text-blue-400 hover:underline flex items-center gap-1 font-medium">
                ← Kembali ke Homepage
            </a>
        </div>
        <h2 class="text-2xl font-bold mb-6 text-center">Daftar Akun Baru</h2>
        <?php if (!empty($error)): ?>
            <div class="mb-4 p-3 bg-red-500/20 border border-red-500 text-red-300 text-sm rounded-lg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="mb-4 p-3 bg-green-500/20 border border-green-500 text-green-300 text-sm rounded-lg"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm text-slate-400 mb-1">Username</label>
                <input type="text" name="username" required class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-blue-500 text-white">
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-blue-500 text-white">
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-blue-500 text-white">
            </div>
            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition">Register</button>
        </form>
        <p class="text-center text-sm text-slate-400 mt-6">
            Sudah punya akun? <a href="login.php" class="text-blue-400 hover:underline">Login</a>
        </p>
    </div>
</body>
</html>
n</a>
        </p>
    </div>
</body>
</html>

