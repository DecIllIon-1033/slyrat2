<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id        = $_SESSION['user_id'];
    $author         = trim($_POST['author']);
    $title          = trim($_POST['title']);
    $type           = $_POST['type'];
    $publisher      = isset($_POST['publisher']) ? trim($_POST['publisher']) : null;
    $year_published = !empty($_POST['year_published']) ? (int)$_POST['year_published'] : null;
    $binding        = !empty($_POST['binding']) ? $_POST['binding'] : null;
    $condition_desc = !empty($_POST['condition_desc']) ? trim($_POST['condition_desc']) : null;

    // Валидация обязательных полей
    if (empty($author) || empty($title) || !in_array($type, ['share', 'want'])) {
        $_SESSION['error'] = 'Заполните все обязательные поля';
        header('Location: cret_card.php');
        exit;
    }

    // Валидация года издания
    if ($year_published !== null && ($year_published < 1800 || $year_published > 2025)) {
        $_SESSION['error'] = 'Некорректный год издания';
        header('Location: cret_card.php');
        exit;
    }

    // Сохранение карточки
    $stmt = $conn->prepare("
        INSERT INTO cards (user_id, author, title, type, publisher, year_published, binding, condition_desc)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        'isssssss',
        $user_id,
        $author,
        $title,
        $type,
        $publisher,
        $year_published,
        $binding,
        $condition_desc
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Карточка успешно создана и отправлена на модерацию';
        header('Location: my_card.php');
    } else {
        $_SESSION['error'] = 'Ошибка при создании карточки: ' . $conn->error;
        header('Location: crete_card.php');
    }

    exit;
}
?>
