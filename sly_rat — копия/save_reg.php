<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login    = $_POST['login'];
    $password = $_POST['password'];
    $fullname = $_POST['fullname'];
    $phone    = $_POST['phone'];
    $email    = $_POST['email'];

    // Валидация данных
    if (mb_strlen($login) < 6 || !preg_match('/^[А-Яа-яЁё]+$/u', $login)) {
        $_SESSION['error'] = 'Логин должен содержать минимум 6 символов кириллицы';
        header('Location: reg.php');
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = 'Пароль должен содержать минимум 6 символов';
        header('Location: reg.php');
        exit;
    }

    if (!preg_match('/^[А-Яа-яЁё\s]+$/u', $fullname)) {
        $_SESSION['error'] = 'ФИО должно содержать только кириллицу и пробелы';
        header('Location: reg.php');
        exit;
    }

    if (!preg_match('/^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/', $phone)) {
        $_SESSION['error'] = 'Неверный формат телефона';
        header('Location: reg.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Неверный формат email';
        header('Location: reg.php');
        exit;
    }

    // Проверка логина на уникальность
    $checkQuery = $conn->prepare('SELECT id FROM users WHERE login = ?');
    $checkQuery->bind_param('s', $login);
    $checkQuery->execute();
    $checkResult = $checkQuery->get_result();

    if ($checkResult->num_rows > 0) {
        $_SESSION['error'] = 'Пользователь с таким логином уже существует';
        header('Location: reg.php');
        exit;
    }

    // Хеширование пароля и запись в БД
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $insertQuery = $conn->prepare('INSERT INTO users (login, password, fullname, phone, email) VALUES (?, ?, ?, ?, ?)');
    $insertQuery->bind_param('sssss', $login, $hashedPassword, $fullname, $phone, $email);

    if ($insertQuery->execute()) {
        $_SESSION['success'] = 'Регистрация успешна! Войдите в систему.';
        header('Location: index.php');
    } else {
        $_SESSION['error'] = 'Ошибка при регистрации: ' . $conn->error;
        header('Location: reg.php');
    }

    exit;
}
?>