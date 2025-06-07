<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Получаем активные карточки
$stmt = $conn->prepare("
    SELECT * FROM cards
    WHERE user_id = ? AND status != 'Архив'
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$active_cards = $stmt->get_result();

// Получаем архивные карточки
$stmt = $conn->prepare("
    SELECT * FROM cards
    WHERE user_id = ? AND status = 'Архив'
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$archived_cards = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Мои карточки - Буквоежка</title>
    <link rel="stylesheet" href="sly/style.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="container">
        <h1>Мои карточки</h1>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <h2>Активные карточки</h2>
        <div class="card-list">
            <?php if($active_cards->num_rows > 0): ?>
                <?php while($card = $active_cards->fetch_assoc()): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($card['title']); ?></h3>
                        <p><strong>Автор:</strong> <?php echo htmlspecialchars($card['author']); ?></p>
                        <p><strong>Тип:</strong> <?php echo $card['type'] === 'share' ? 'Готов поделиться' : 'Хочу в библиотеку'; ?></p>
                        <?php if($card['publisher']): ?>
                            <p><strong>Издательство:</strong> <?php echo htmlspecialchars($card['publisher']); ?></p>
                        <?php endif; ?>
                        <?php if($card['year_published']): ?>
                            <p><strong>Год издания:</strong> <?php echo htmlspecialchars($card['year_published']); ?></p>
                        <?php endif; ?>
                        <?php if($card['binding']): ?>
                            <p><strong>Переплет:</strong> <?php echo htmlspecialchars($card['binding']); ?></p>
                        <?php endif; ?>
                        <?php if($card['condition_desc']): ?>
                            <p><strong>Состояние:</strong> <?php echo htmlspecialchars($card['condition_desc']); ?></p>
                        <?php endif; ?>
                        <p><strong>Статус:</strong> <span class="status status-<?php echo strtolower(str_replace(' ', '', $card['status'])); ?>">
                            <?php echo $card['status']; ?>
                        </span></p>
                        <?php if($card['rejection_reason']): ?>
                            <p><strong>Причина отклонения:</strong> <?php echo htmlspecialchars($card['rejection_reason']); ?></p>
                        <?php endif; ?>

                        <div class="actions">
                            <form action="arch.php" method="post" style="width: 100%;">
                                <input type="hidden" name="card_id" value="<?php echo $card['id']; ?>">
                                <button type="submit">В архив</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>У вас пока нет активных карточек</p>
            <?php endif; ?>
        </div>

        <h2>Архив</h2>
        <div class="card-list">
            <?php if($archived_cards->num_rows > 0): ?>
                <?php while($card = $archived_cards->fetch_assoc()): ?>
                    <div class="card">
                        <h3><?php echo htmlspecialchars($card['title']); ?></h3>
                        <p><strong>Автор:</strong> <?php echo htmlspecialchars($card['author']); ?></p>
                        <p><strong>Тип:</strong> <?php echo $card['type'] === 'share' ? 'Готов поделиться' : 'Хочу в библиотеку'; ?></p>
                        <?php if($card['publisher']): ?>
                            <p><strong>Издательство:</strong> <?php echo htmlspecialchars($card['publisher']); ?></p>
                        <?php endif; ?>
                        <?php if($card['year_published']): ?>
                            <p><strong>Год издания:</strong> <?php echo htmlspecialchars($card['year_published']); ?></p>
                        <?php endif; ?>
                        <?php if($card['binding']): ?>
                            <p><strong>Переплет:</strong> <?php echo htmlspecialchars($card['binding']); ?></p>
                        <?php endif; ?>
                        <?php if($card['condition_desc']): ?>
                            <p><strong>Состояние:</strong> <?php echo htmlspecialchars($card['condition_desc']); ?></p>
                        <?php endif; ?>
                        <p><strong>Статус:</strong> <span class="status status-архив">Архив</span></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>В архиве пока нет карточек</p>
            <?php endif; ?>
        </div>

        <div class="nav">
            <a href="cret_card.php" class="button">Создать карточку</a>
            <a href="logut.php">Выйти</a>
        </div>
    </div>
</body>
</html>