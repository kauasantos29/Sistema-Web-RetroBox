<?php
require_once __DIR__ . '/../../config/config.php';
exigirLogin();

$titulo = 'Painel';
require __DIR__ . '/topo.php';
?>

<h2>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h2>
<p>Bem-vindo ao painel do RetroBox.</p>

<?php require __DIR__ . '/rodape.php'; ?>
