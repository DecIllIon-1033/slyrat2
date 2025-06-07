<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация - Буквоежка</title>
    <link rel="stylesheet" href="sly/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="container">
        <h1>Регистрация в системе</h1>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="error">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="save_reg.php" method="post" onsubmit="return validateForm();">
            <div class="form-group">
                <label for="fullname">ФИО:</label>
                <input type="text" id="fullname" name="fullname" required
                       pattern="[А-Яа-яЁё\s]+" title="Только кириллица и пробелы">
            </div>

            <div class="form-group">
                <label for="phone">Телефон:</label>
                <input type="tel" id="phone" name="phone" required
                       pattern="\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}"
                       title="Формат: +7(XXX)-XXX-XX-XX">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="login">Логин (минимум 6 символов, кириллица):</label>
                <input type="text" id="login" name="login" required
                       minlength="6" pattern="[А-Яа-яЁё]{6,}"
                       title="Минимум 6 символов, только кириллица">
            </div>

            <div class="form-group">
                <label for="password">Пароль (минимум 6 символов):</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>

            <div id="error" class="error" style="display: none;"></div>

            <button type="submit">Зарегистрироваться</button>
        </form>

        <div class="nav">
            <a href="index.php">Уже есть аккаунт? Войти</a>
        </div>
    </div>

    <script>
        function validateForm() {
            const error = document.getElementById('error');
            const fields = {
                login: {
                    value: document.getElementById('login').value,
                    regex: /^[А-Яа-яЁё]{6,}$/,
                    message: 'Логин должен содержать минимум 6 символов кириллицы'
                },
                password: {
                    value: document.getElementById('password').value,
                    valid: val => val.length >= 6,
                    message: 'Пароль должен содержать минимум 6 символов'
                },
                fullname: {
                    value: document.getElementById('fullname').value,
                    regex: /^[А-Яа-яЁё\s]+$/,
                    message: 'ФИО должно содержать только кириллицу и пробелы'
                },
                phone: {
                    value: document.getElementById('phone').value,
                    regex: /^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/,
                    message: 'Телефон должен быть в формате +7(XXX)-XXX-XX-XX'
                },
                email: {
                    value: document.getElementById('email').value,
                    regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                    message: 'Введите корректный email адрес'
                }
            };

            for (let key in fields) {
                const field = fields[key];
                const isValid = field.regex ? field.regex.test(field.value) : field.valid(field.value);
                if (!isValid) {
                    error.textContent = field.message;
                    error.style.display = 'block';
                    return false;
                }
            }

            error.style.display = 'none';
            return true;
        }

        // Маска телефона
        document.getElementById('phone').addEventListener('input', function (e) {
            let cleaned = e.target.value.replace(/\D/g, '');
            let formatted = '+7(';
            if (cleaned.length > 0) formatted += cleaned.slice(0, 3);
            if (cleaned.length >= 4) formatted += ')-' + cleaned.slice(3, 6);
            if (cleaned.length >= 7) formatted += '-' + cleaned.slice(6, 8);
            if (cleaned.length >= 9) formatted += '-' + cleaned.slice(8, 10);
            e.target.value = formatted;
        });
    </script>
</body>
</html>
