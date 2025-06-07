<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['user_id']) || !isset($_POST['card_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$card_id = $_POST['card_id'];

// Проверяем, принадлежит ли карточка пользователю
$stmt = $conn->prepare("SELECT id FROM cards WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $card_id, $user_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    $_SESSION['error'] = "Доступ запрещен";
    header("Location: my_card.php");
    exit();
}

// Обновляем статус карточки
$stmt = $conn->prepare("UPDATE cards SET status = 'Архив' WHERE id = ?");
$stmt->bind_param("i", $card_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Карточка перемещена в архив";
} else {
    $_SESSION['error'] = "Ошибка при архивации карточки";
}

header("Location: my_card.php");
?>