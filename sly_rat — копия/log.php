<?php
session_start();
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    // Проверка для админа
    if ($login === 'admin' && $password === 'bookworm') {
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Неверный логин или пароль";
        header("Location: index.php");
        exit();
    }

    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: my_card.php");
        exit();
    } else {
        $_SESSION['error'] = "Неверный логин или пароль";
        header("Location: index.php");
        exit();
    }
}
?>
