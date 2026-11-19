<?php
require_once __DIR__ . '/../../config/config.php';

if (clienteLogado()) {
    header('Location: ' . BASE_URL);
    exit;
}

$voltar = ($_GET['voltar'] ?? '') === 'carrinho' ? 'carrinho' : '';

$erros = [
    'dados' => 'Confira o nome, o e-mail e o telefone.',
    'senha' => 'A senha precisa ter pelo menos 6 caracteres.',
    'confirmar' => 'As senhas não são iguais.',
    'email' => 'Esse e-mail já está cadastrado.',
];
$erro = $erros[$_GET['erro'] ?? ''] ?? null;

$titulo = 'Criar conta - RetroBox';
require __DIR__ . '/topo.php';
?>

<main class="container">
    <div class="caixa login">
        <h2>Criar conta</h2>

        <?php if ($erro): ?>
            <div class="msg erro"><?= $erro ?></div>
        <?php endif; ?>

        <div class="msg erro" id="erro-js" hidden></div>

        <form id="form-cliente" action="<?= BASE_URL ?>controllers/ClienteLojaController.php?acao=cadastrar" method="POST">
            <input type="hidden" name="voltar" value="<?= $voltar ?>">

            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" required>

            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" required>

            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" placeholder="(38) 99999-9999" maxlength="15" required>

            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" required>

            <label for="confirmar_senha">Confirmar senha</label>
            <input type="password" name="confirmar_senha" id="confirmar_senha" required>

            <button type="submit">Criar conta</button>
        </form>

        <p>Já tem conta? <a href="entrar.php<?= $voltar ? '?voltar=carrinho' : '' ?>">Entrar</a></p>
    </div>
</main>

<script src="<?= BASE_URL ?>public/js/cliente.js"></script>

<?php require __DIR__ . '/rodape.php'; ?>
