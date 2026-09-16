<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && isset($user['password_hash']) && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];

                if ($user['role'] === 'admin') {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: ../dashboard.php");
                }
                exit;
            } else {
                $error = "Email atau password salah!";
            }
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan database: " . $e->getMessage();
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
    <title>Login - Trading Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white flex items-center justify-center min-h-screen px-4">
    <div class="bg-slate-800 p-8 rounded-2xl shadow-xl w-full max-w-md border border-slate-700">
        <div class="mb-6">
            <a href="/" class="text-sm text-blue-400 hover:underline flex items-center gap-1 font-medium">
                ← Kembali ke Homepage
            </a>
        </div>
        <h2 class="text-2xl font-bold mb-6 text-center">Login ke Akun Anda</h2>
        <?php if (!empty($error)): ?>
            <div class="mb-4 p-3 bg-red-500/20 border border-red-500 text-red-300 text-sm rounded-lg">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm text-slate-400 mb-1">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-blue-500 text-white">
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:border-blue-500 text-white">
            </div>
            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold transition">Login</button>
        </form>
        <p class="text-center text-sm text-slate-400 mt-6">
            Belum punya akun? <a href="register.php" class="text-blue-400 hover:underline">Register</a>
        </p>
    </div>
</body>
</html>