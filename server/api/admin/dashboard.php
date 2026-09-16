<?php
require_once __DIR__ . '/../../config/database.php';
require_admin();

$db = (new Database())->getConnection();

// Logika Hapus Modul
if (isset($_GET['delete_id'])) {
    $stmt = $db->prepare("DELETE FROM lessons WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    header("Location: dashboard.php?msg=deleted");
    exit;
}

// Logika Tambah & Edit Modul
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $level_id = $_POST['level_id'];
    $title = $_POST['title'];
    $lesson_order = $_POST['lesson_order'];
    
    if (isset($_POST['add_lesson'])) {
        $desc = "Deskripsi materi " . $title;
        $stmt = $db->prepare("INSERT INTO lessons (level_id, title, description, lesson_order) VALUES (?, ?, ?, ?)");
        $stmt->execute([$level_id, $title, $desc, $lesson_order]);
        header("Location: dashboard.php?msg=added");
        exit;
    } elseif (isset($_POST['update_lesson'])) {
        $lesson_id = $_POST['lesson_id'];
        $stmt = $db->prepare("UPDATE lessons SET level_id = ?, title = ?, lesson_order = ? WHERE id = ?");
        $stmt->execute([$level_id, $title, $lesson_order, $lesson_id]);
        header("Location: dashboard.php?msg=updated");
        exit;
    }
}

$edit_lesson = null;
if (isset($_GET['edit_id'])) {
    $stmt = $db->prepare("SELECT * FROM lessons WHERE id = ?");
    $stmt->execute([$_GET['edit_id']]);
    $edit_lesson = $stmt->fetch();
}

$total_users = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$levels = $db->query("SELECT id, title FROM levels ORDER BY level_order")->fetchAll();
$lessons = $db->query("SELECT l.id, l.title, lv.title as level_title, l.lesson_order FROM lessons l JOIN levels lv ON l.level_id = lv.id ORDER BY l.level_id, l.lesson_order")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TradingLearn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen">
    <nav class="bg-slate-900 text-white border-b border-slate-800 sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-5 py-4 flex flex-wrap gap-4 items-center justify-between">
            <b class="text-xl text-blue-400">TradingLearn · Admin</b>
            <div class="flex flex-wrap gap-4 text-sm font-semibold">
                <a href="../dashboard.php" class="hover:text-blue-400">Student Mode</a>
                <a href="../auth/logout.php" class="text-red-400">Logout</a>
            </div>
        </div>
    </nav>
    <main class="max-w-6xl mx-auto p-5 md:p-8 space-y-8">
        <div>
            <h1 class="text-3xl font-black mt-1">Admin Dashboard ⚙️</h1>
            <?php if(isset($_GET['msg'])): ?>
                <?php if($_GET['msg'] === 'added'): ?>
                    <div class="mt-4 p-3 bg-green-100 text-green-700 border border-green-300 rounded-lg text-sm font-bold">✅ Berhasil menambahkan modul baru!</div>
                <?php elseif($_GET['msg'] === 'updated'): ?>
                    <div class="mt-4 p-3 bg-blue-100 text-blue-700 border border-blue-300 rounded-lg text-sm font-bold">✅ Modul berhasil diperbarui!</div>
                <?php elseif($_GET['msg'] === 'deleted'): ?>
                    <div class="mt-4 p-3 bg-red-100 text-red-700 border border-red-300 rounded-lg text-sm font-bold">🗑️ Modul berhasil dihapus!</div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-3xl border shadow-sm overflow-hidden p-6 <?= $edit_lesson ? 'ring-2 ring-blue-500' : '' ?>">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-lg <?= $edit_lesson ? 'text-blue-600' : 'text-blue-900' ?>">
                    <?= $edit_lesson ? '✏️ Edit Modul' : '➕ Tambah Modul Baru' ?>
                </h2>
                <?php if($edit_lesson): ?>
                    <a href="dashboard.php" class="text-xs bg-slate-200 hover:bg-slate-300 text-slate-700 px-3 py-1 rounded font-bold">Batal Edit</a>
                <?php endif; ?>
            </div>

            <form method="POST" class="grid sm:grid-cols-4 gap-4 items-end">
                <?php if($edit_lesson): ?>
                    <input type="hidden" name="lesson_id" value="<?= $edit_lesson['id'] ?>">
                <?php endif; ?>

                <div class="sm:col-span-1">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Pilih Kategori (Level)</label>
                    <select name="level_id" class="w-full border p-2 rounded bg-slate-50" required>
                        <?php foreach($levels as $lv): ?>
                            <option value="<?= $lv['id'] ?>" <?= ($edit_lesson && $edit_lesson['level_id'] == $lv['id']) ? 'selected' : '' ?>>
                                <?= e($lv['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Judul Modul</label>
                    <input type="text" name="title" value="<?= $edit_lesson ? e($edit_lesson['title']) : '' ?>" placeholder="Contoh: Support & Resistance" class="w-full border p-2 rounded bg-slate-50" required>
                </div>
                <div class="sm:col-span-1">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Urutan</label>
                    <div class="flex gap-2">
                        <input type="number" name="lesson_order" value="<?= $edit_lesson ? e($edit_lesson['lesson_order']) : '1' ?>" min="1" class="w-20 border p-2 rounded bg-slate-50" required>
                        
                        <?php if($edit_lesson): ?>
                            <button type="submit" name="update_lesson" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold rounded p-2 transition">Simpan</button>
                        <?php else: ?>
                            <button type="submit" name="add_lesson" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold rounded p-2 transition">+ Tambah</button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-3xl border shadow-sm overflow-hidden">
            <div class="p-6 border-b bg-slate-50">
                <h2 class="font-bold text-lg">Daftar Modul Saat Ini</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-500 text-sm border-b">
                            <th class="p-4 font-semibold">Urutan</th>
                            <th class="p-4 font-semibold">Judul Modul</th>
                            <th class="p-4 font-semibold">Kategori Level</th>
                            <th class="p-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lessons as $l): ?>
                        <tr class="border-b hover:bg-slate-50">
                            <td class="p-4 font-bold text-slate-400"><?= e($l['lesson_order']) ?></td>
                            <td class="p-4 font-semibold"><?= e($l['title']) ?></td>
                            <td class="p-4 text-sm text-blue-600 font-medium"><?= e($l['level_title']) ?></td>
                            <td class="p-4 text-right space-x-2">
                                <a href="dashboard.php?edit_id=<?= $l['id'] ?>" class="text-xs px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded font-semibold inline-block">Edit</a>
                                <a href="dashboard.php?delete_id=<?= $l['id'] ?>" onclick="return confirm('Yakin ingin menghapus modul ini?')" class="text-xs px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 rounded font-semibold inline-block">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>