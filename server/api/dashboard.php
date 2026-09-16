<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/server/config/database.php';
require_login();
$db = (new Database())->getConnection(); 
$uid = $_SESSION['user_id'];

$s = $db->prepare("SELECT COUNT(*) total, COALESCE(SUM(result='Win'),0) wins, COALESCE(SUM(result='Loss'),0) losses, COALESCE(AVG(risk_reward),0) avg_rr FROM trading_journal WHERE user_id=?"); 
$s->execute([$uid]); 
$stats = $s->fetch();

$l = $db->query("SELECT COUNT(*) FROM lessons")->fetchColumn(); 
$done = $db->prepare("SELECT COUNT(*) FROM lesson_progress WHERE user_id=? AND completed=1"); 
$done->execute([$uid]); 
$done = $done->fetchColumn();
$progress = $l ? round($done / $l * 100) : 0;

function nav($active) {
    $items = ['dashboard' => 'Dashboard', 'roadmap' => 'Roadmap', 'journal' => 'Journal', 'quiz' => 'Quiz', 'backtest' => 'Backtest'];
    foreach($items as $k => $v) {
        echo '<a class="' . ($active === $k ? 'text-blue-600 font-bold' : 'text-slate-600') . ' hover:text-blue-600" href="' . $k . '.php">' . $v . '</a>';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard · TradingLearn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900">
    <nav class="bg-white border-b sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-5 py-4 flex flex-wrap gap-4 items-center justify-between">
            <b class="text-xl text-blue-600">TradingLearn</b>
            <div class="flex flex-wrap gap-4 text-sm">
                <?php nav('dashboard'); ?>
                <a href="auth/logout.php" class="text-red-600">Logout</a>
            </div>
        </div>
    </nav>
    <main class="max-w-6xl mx-auto p-5 md:p-8">
        <div class="mb-7">
            <p class="text-sm text-slate-500">Student Dashboard</p>
            <h1 class="text-3xl font-black mt-1">Halo, <?= e($_SESSION['username']) ?> 👋</h1>
            <p class="text-slate-500 mt-2">Belajar market dengan proses, bukan sekadar mengejar hasil.</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <?php foreach([['Total Trade', $stats['total'], 'text-slate-900'], ['Win Rate', ($stats['total'] ? round($stats['wins'] / $stats['total'] * 100, 1) : 0) . '%', 'text-emerald-600'], ['Avg R:R', '1 : ' . round($stats['avg_rr'], 2), 'text-blue-600'], ['Progress', $progress . '%', 'text-violet-600']] as $c): ?>
                <div class="bg-white p-5 rounded-2xl border shadow-sm">
                    <p class="text-sm text-slate-500"><?= $c[0] ?></p>
                    <div class="text-2xl font-black mt-2 <?= $c[2] ?>"><?= $c[1] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="grid lg:grid-cols-3 gap-5">
            <section class="lg:col-span-2 bg-slate-900 text-white rounded-3xl p-7">
                <p class="text-blue-300 text-sm font-semibold">LEARNING PROGRESS</p>
                <h2 class="text-2xl font-bold mt-2">Progress materi <?= $progress ?>%</h2>
                <div class="h-3 bg-slate-700 rounded-full mt-5 overflow-hidden">
                    <div class="h-full bg-blue-500" style="width:<?= $progress ?>%"></div>
                </div>
                <a href="roadmap.php" class="inline-block mt-6 bg-white text-slate-900 px-5 py-3 rounded-xl font-semibold">Lanjut Belajar →</a>
            </section>
            <section class="bg-white rounded-3xl p-6 border shadow-sm">
                <h2 class="font-bold text-lg">Tools Belajar</h2>
                <div class="mt-4 grid gap-3">
                    <a class="p-4 rounded-xl bg-slate-50 hover:bg-blue-50" href="journal.php"><b>📓 Trading Journal</b><p class="text-xs text-slate-500 mt-1">Catat setup dan evaluasi.</p></a>
                    <a class="p-4 rounded-xl bg-slate-50 hover:bg-blue-50" href="backtest.php"><b>🧪 Backtest Simulator</b><p class="text-xs text-slate-500 mt-1">Simulasi XAU/USD chart.</p></a>
                    <a class="p-4 rounded-xl bg-slate-50 hover:bg-blue-50" href="quiz.php"><b>🧠 Quiz</b><p class="text-xs text-slate-500 mt-1">Uji pemahaman materi.</p></a>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
