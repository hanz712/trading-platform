<?php
// Halaman Utama Trading Learning Platform
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trading Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white font-sans min-h-screen flex flex-col justify-between">
    <header class="flex justify-between items-center p-5 border-b border-slate-800 max-w-6xl mx-auto w-full">
        <h1 class="text-xl font-bold text-blue-400">Trading Learning Platform</h1>
        <div class="flex items-center gap-3">
            <a href="server/api/auth/login.php" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg transition font-medium text-sm text-white">Masuk</a>
            <a href="server/api/auth/register.php" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg transition font-medium text-sm text-white">Daftar</a>
        </div>
    </header>
    <main class="max-w-4xl mx-auto text-center py-16 px-4 flex-1 flex flex-col justify-center">
        <h2 class="text-3xl md:text-5xl font-extrabold mb-6 leading-tight">Belajar Trading & Backtesting XAUUSD Profesional</h2>
        <p class="text-slate-400 mb-8 text-base md:text-lg">Platform edukasi trading lengkap dengan simulasi backtesting data XAUUSD asli, manajemen jurnal, dan roadmap terstruktur.</p>
        <div>
            <a href="server/api/auth/login.php" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-base font-semibold rounded-xl shadow-lg transition inline-block text-white">Mulai Belajar Sekarang</a>
        </div>
    </main>
    <footer class="text-center py-6 text-xs text-slate-500 border-t border-slate-800">
        &copy; 2026 Trading Learning Platform. By Han.
    </footer>
</body>
</html>