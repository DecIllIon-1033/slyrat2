<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Создание карточки - Буквоежка</title>
 <link rel="stylesheet" href="sly/style.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="container">
        <h1>Создание карточки</h1>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="save_card.php" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label>Автор книги:</label>
                <input type="text" name="author" required>
            </div>

            <div class="form-group">
                <label>Название книги:</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label>Тип карточки:</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="type" value="share" required> Готов поделиться
                    </label>
                    <label>
                        <input type="radio" name="type" value="want" required> Хочу в свою библиотеку
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Издательство (необязательно):</label>
                <input type="text" name="publisher">
            </div>

            <div class="form-group">
                <label>Год издания (необязательно):</label>
                <input type="number" name="year_published" min="1800" max="2025">
            </div>

            <div class="form-group">
                <label>Переплет (необязательно):</label>
                <select name="binding">
                    <option value="">Выберите тип переплета</option>
                    <option value="Твердый">Твердый</option>
                    <option value="Мягкий">Мягкий</option>
                    <option value="Интегральный">Интегральный</option>
                </select>
            </div>

            <div class="form-group">
                <label>Состояние книги (необязательно):</label>
                <textarea name="condition_desc" rows="3"></textarea>
            </div>

            <div id="error" class="error" style="display: none;"></div>
            <button type="submit">Отправить</button>
        </form>

        <div class="nav">
            <a href="my_card.php">Назад к моим карточкам</a>
        </div>
    </div>

    <script>
    function validateForm() {
        const author = document.querySelector('input[name="author"]').value;
        const title = document.querySelector('input[name="title"]').value;
        const type = document.querySelector('input[name="type"]:checked');
        const error = document.getElementById('error');

        if (!author.trim()) {
            error.textContent = 'Введите автора книги';
            error.style.display = 'block';
            return false;
        }

        if (!title.trim()) {
            error.textContent = 'Введите название книги';
            error.style.display = 'block';
            return false;
        }

        if (!type) {
            error.textContent = 'Выберите тип карточки';
            error.style.display = 'block';
            return false;
        }

        return true;
    }
    </script>
</body>
</html>