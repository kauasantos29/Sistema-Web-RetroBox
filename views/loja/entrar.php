<?php
require_once __DIR__ . '/../../config/config.php';

if (clienteLogado()) {
    header('Location: ' . BASE_URL);
    exit;
}

$voltar = ($_GET['voltar'] ?? '') === 'carrinho' ? 'carrinho' : '';

$titulo = 'Entrar - RetroBox';
require __DIR__ . '/topo.php';
?>

<main class="container">
    <div class="caixa login">
        <h2>Entrar</h2>

        <?php if (isset($_GET['erro'])): ?>
            <div class="msg erro">E-mail ou senha incorretos.</div>
        <?php elseif ($voltar): ?>
            <div class="msg ok">Entre na sua conta para finalizar o pedido.</div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>controllers/ClienteLojaController.php?acao=entrar" method="POST">
            <input type="hidden" name="voltar" value="<?= $voltar ?>">

            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" required>

            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" required>

            <button type="submit">Entrar</button>
        </form>

        <p>Ainda não tem conta? <a href="cadastro.php<?= $voltar ? '?voltar=carrinho' : '' ?>">Criar conta</a></p>
    </div>
</main>

<?php require __DIR__ . '/rodape.php'; ?>
