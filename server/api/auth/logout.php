<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit;
?>
okies")) { $p=session_get_cookie_params(); setcookie(session_name(),'',
time()-42000,$p["path"],$p["domain"],$p["secure"],$p["httponly"]); } session_destroy();
header("Location: login.php"); exit;
