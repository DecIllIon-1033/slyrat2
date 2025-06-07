<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="sly/style.css"/>
</head>
<body>
    <div class="container">
        <h1>Буквоежка</h1>
        <p style="text-align: center; margin-bottom: 30px;">Система обмена книгами</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form action="log.php" method="post">
            <div class="form-group">
                <label>Логин:</label>
                <input type="text" name="login" required>
            </div>

            <div class="form-group">
                <label>Пароль:</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Войти</button>
        </form>

        <div class="nav">
            <a href="reg.php">Зарегистрироваться</a>
            <a href="admin.php">Панель администратора</a>
        </div>
    </div>
</body>
</html>

