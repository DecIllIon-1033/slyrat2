<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['admin']) || !isset($_POST['card_id']) || !isset($_POST['action'])) {
    header("Location: admin.php");
    exit();
}

$card_id = $_POST['card_id'];
$action = $_POST['action'];
$rejection_reason = isset($_POST['rejection_reason']) ? trim($_POST['rejection_reason']) : null;

if ($action === 'approve') {
    $stmt = $conn->prepare("UPDATE cards SET status = 'Опубликована' WHERE id = ?");
    $stmt->bind_param("i", $card_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Карточка опубликована";
    } else {
        $_SESSION['error'] = "Ошибка при публикации карточки";
    }
} elseif ($action === 'reject') {
    if (empty($rejection_reason)) {
        $_SESSION['error'] = "Укажите причину отклонения";
        header("Location: admin.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE cards SET status = 'Отклонена', rejection_reason = ? WHERE id = ?");
    $stmt->bind_param("si", $rejection_reason, $card_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Карточка отклонена";
    } else {
        $_SESSION['error'] = "Ошибка при отклонении карточки";
    }
}

header("Location: admin.php");
?>