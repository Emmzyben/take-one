<?php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header('Location: index.php');
exit();
?>
