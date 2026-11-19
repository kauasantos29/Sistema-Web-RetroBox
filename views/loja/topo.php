<?php $qtdCarrinho = array_sum($_SESSION['carrinho'] ?? []); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'RetroBox' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>
<body>
    <header class="topo">
        <a class="logo" href="<?= BASE_URL ?>">RetroBox</a>
        <nav>
            <a href="<?= BASE_URL ?>">Jogos</a>
            <a href="<?= BASE_URL ?>views/loja/carrinho.php">Carrinho (<?= $qtdCarrinho ?>)</a>
            <?php if (clienteLogado()): ?>
                <a href="<?= BASE_URL ?>views/loja/pedidos.php">Meus pedidos</a>
                <a href="<?= BASE_URL ?>controllers/ClienteLojaController.php?acao=sair">Sair (<?= htmlspecialchars(explode(' ', $_SESSION['cliente_nome'])[0]) ?>)</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>views/loja/entrar.php">Entrar</a>
                <a href="<?= BASE_URL ?>views/loja/cadastro.php">Criar conta</a>
            <?php endif; ?>
        </nav>
    </header>
