<?php
session_start();
$_SESSION['theme'] = ($_SESSION['theme'] === 'dark') ? 'light' : 'dark';
header("Location: settings.php");
exit;
?>
