<?php
require_once __DIR__ . '/../config/database.php'; require_login(); $db=(new Database())->getConnection(); $uid=$_SESSION['user_id']; $id=(int)($_GET['id']??0);
$s=$db->prepare("SELECT l.*,lv.title level_title,lv.level_order FROM lessons l JOIN levels lv ON lv.id=l.level_id WHERE l.id=?"); $s->execute([$id]); $lesson=$s->fetch();
if(!$lesson){http_response_code(404);die("Lesson tidak ditemukan.");}
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$s=$db->prepare("INSERT INTO lesson_progress(user_id,lesson_id,completed,completed_at) VALUES(?,?,1,NOW()) ON DUPLICATE KEY UPDATE completed=1,completed_at=NOW()");$s->execute([$uid,$id]);header("Location: lesson.php?id=".$id."&done=1");exit;}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($lesson['title'])?> · TradingLearn</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-slate-50"><nav class="bg-white border-b"><div class="max-w-4xl mx-auto p-4 flex justify-between"><a class="font-bold text-blue-600" href="roadmap.php">← Roadmap</a><a href="dashboard.php">Dashboard</a></div></nav><main class="max-w-4xl mx-auto p-5 md:p-8"><span class="text-xs font-bold text-blue-600">LEVEL <?=$lesson['level_order']?> · LESSON <?=$lesson['lesson_order']?></span><h1 class="text-3xl font-black mt-2"><?=e($lesson['title'])?></h1><p class="text-slate-500 mt-2"><?=e($lesson['description'])?></p>
<?php if(!empty($lesson['video_url'])): ?><a target="_blank" rel="noopener" href="<?=e($lesson['video_url'])?>" class="inline-block mt-5 px-4 py-2 bg-red-600 text-white rounded-xl">▶ Buka Video</a><?php endif; ?>
<article class="mt-7 bg-white rounded-3xl border shadow-sm p-6 md:p-8 whitespace-pre-line leading-7 text-slate-700"><?=e($lesson['content'])?></article>
<form method="post" class="mt-5"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><button class="w-full p-4 rounded-2xl bg-blue-600 text-white font-bold">✅ Tandai Materi Selesai</button></form></main></body></html>
