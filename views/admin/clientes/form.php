<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../models/Cliente.php';
exigirLogin();

$id = (int) ($_GET['id'] ?? 0);
$dados = ['id' => 0, 'nome' => '', 'email' => '', 'telefone' => ''];

if ($id > 0) {
    $cliente = new Cliente();
    $dados = $cliente->buscarPorId($id);

    if (!$dados) {
        header('Location: index.php');
        exit;
    }
}

$erros = [
    'dados' => 'Confira o nome, o e-mail e o telefone.',
    'senha' => 'A senha precisa ter pelo menos 6 caracteres.',
    'confirmar' => 'As senhas não são iguais.',
    'email' => 'Já existe um cliente com esse e-mail.',
];
$erro = $erros[$_GET['erro'] ?? ''] ?? null;

$titulo = $id ? 'Editar cliente' : 'Novo cliente';
require __DIR__ . '/../topo.php';
?>

<h2><?= $titulo ?></h2>

<?php if ($erro): ?>
    <div class="msg erro"><?= $erro ?></div>
<?php endif; ?>

<div class="msg erro" id="erro-js" hidden></div>

<div class="caixa form">
    <form id="form-cliente" action="<?= BASE_URL ?>controllers/ClienteController.php?acao=salvar" method="POST">
        <input type="hidden" name="id" value="<?= $dados['id'] ?>">

        <label for="nome">Nome</label>
        <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($dados['nome']) ?>" required>

        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($dados['email']) ?>" required>

        <label for="telefone">Telefone</label>
        <input type="text" name="telefone" id="telefone" placeholder="(38) 99999-9999" maxlength="15" value="<?= htmlspecialchars($dados['telefone']) ?>" required>

        <div class="linha">
            <div>
                <label for="senha">Senha<?= $id ? ' (deixe vazio para manter)' : '' ?></label>
                <input type="password" name="senha" id="senha" <?= $id ? '' : 'required' ?>>
            </div>
            <div>
                <label for="confirmar_senha">Confirmar senha</label>
                <input type="password" name="confirmar_senha" id="confirmar_senha" <?= $id ? '' : 'required' ?>>
            </div>
        </div>

        <button type="submit">Salvar</button>
        <a class="voltar" href="index.php">Cancelar</a>
    </form>
</div>

<script src="<?= BASE_URL ?>public/js/cliente.js"></script>

<?php require __DIR__ . '/../rodape.php'; ?>
