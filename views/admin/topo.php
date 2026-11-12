<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Painel' ?> - RetroBox</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>
<body>
    <header class="topo">
        <span class="logo">RetroBox</span>
        <nav>
            <a href="<?= BASE_URL ?>views/admin/painel.php">Painel</a>
            <a href="<?= BASE_URL ?>views/admin/plataformas/index.php">Plataformas</a>
            <a href="<?= BASE_URL ?>views/admin/jogos/index.php">Jogos</a>
            <a href="<?= BASE_URL ?>views/admin/clientes/index.php">Clientes</a>
            <a href="<?= BASE_URL ?>controllers/LoginController.php?acao=sair">Sair</a>
        </nav>
    </header>
    <main class="container">
