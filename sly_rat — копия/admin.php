<?php
session_start();
require_once('db.php');

// Проверка авторизации админа
if (!isset($_SESSION['admin'])) {
    if (!empty($_POST['login']) && !empty($_POST['password'])) {
        if ($_POST['login'] === 'admin' && $_POST['password'] === 'bookworm') {
            $_SESSION['admin'] = true;
        } else {
            $_SESSION['error'] = "Неверный логин или пароль";
            header("Location: admin.php");
            exit();
        }
    } else {
        ?>
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <title>Админ-панель - Буквоежка</title>
            <link rel="stylesheet" href="sly/style.css"/>
            <meta name="viewport" content="width=device-width, initial-scale=1">
        </head>
        <body>
            <div class="container">
                <h1>Вход в панель администратора</h1>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form method="post">
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
                    <a href="index.php">На главную</a>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
}

// Получение карточек на модерации
$stmt = $conn->prepare("
    SELECT c.*, u.fullname, u.phone, u.email
    FROM cards c
    JOIN users u ON c.user_id = u.id
    WHERE c.status = 'На рассмотрении'
    ORDER BY c.created_at DESC
");
$stmt->execute();
$pending_cards = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель - Буквоежка</title>
    <link rel="stylesheet" href="./style/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container">
    <h1>Панель администратора</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <h2>Карточки на модерации</h2>
    <div class="card-list">
        <?php if ($pending_cards->num_rows > 0): ?>
            <?php while ($card = $pending_cards->fetch_assoc()): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($card['title']); ?></h3>
                    <p><strong>Автор:</strong> <?= htmlspecialchars($card['author']); ?></p>
                    <p><strong>Тип:</strong> <?= $card['type'] === 'share' ? 'Готов поделиться' : 'Хочу в библиотеку'; ?></p>

                    <?php if ($card['publisher']): ?>
                        <p><strong>Издательство:</strong> <?= htmlspecialchars($card['publisher']); ?></p>
                    <?php endif; ?>

                    <?php if ($card['year_published']): ?>
                        <p><strong>Год издания:</strong> <?= htmlspecialchars($card['year_published']); ?></p>
                    <?php endif; ?>

                    <?php if ($card['binding']): ?>
                        <p><strong>Переплет:</strong> <?= htmlspecialchars($card['binding']); ?></p>
                    <?php endif; ?>

                    <?php if ($card['condition_desc']): ?>
                        <p><strong>Состояние:</strong> <?= htmlspecialchars($card['condition_desc']); ?></p>
                    <?php endif; ?>

                    <p><strong>Пользователь:</strong> <?= htmlspecialchars($card['fullname']); ?></p>
                    <p><strong>Телефон:</strong> <?= htmlspecialchars($card['phone']); ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($card['email']); ?></p>

                    <div class="actions" style="margin-top: 20px;">
                        <form action="moder_card.php" method="post">
                            <input type="hidden" name="card_id" value="<?= $card['id']; ?>">
                            <div class="form-group" style="margin-bottom: 10px;">
                                <textarea name="rejection_reason" placeholder="Причина отклонения (если отклоняете)" rows="2"></textarea>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <button type="submit" name="action" value="approve" style="background-color: #27ae60;">Опубликовать</button>
                                <button type="submit" name="action" value="reject" style="background-color: #e74c3c;">Отклонить</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Нет карточек на модерации</p>
        <?php endif; ?>
    </div>

    <div class="nav">
        <a href="admin_log.php">Выйти</a>
    </div>
</div>
</body>
</html>
