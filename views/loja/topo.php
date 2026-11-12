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
            <a href="<?= BASE_URL ?>views/login.php">Área administrativa</a>
        </nav>
    </header>
