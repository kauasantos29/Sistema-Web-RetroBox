<?php
require_once __DIR__ . '/../config/config.php';

if (usuarioLogado()) {
    header('Location: ' . BASE_URL . 'views/admin/painel.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RetroBox</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>
<body>
    <div class="caixa login">
        <h1 class="logo">RetroBox</h1>
        <p>Área administrativa</p>

        <?php if (isset($_GET['erro'])): ?>
            <div class="msg erro">E-mail ou senha incorretos.</div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>controllers/LoginController.php" method="POST">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" required>

            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" required>

            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
