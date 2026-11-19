<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../models/Usuario.php';
exigirAdmin();

$id = (int) ($_GET['id'] ?? 0);
$dados = ['id' => 0, 'nome' => '', 'email' => '', 'tipo' => 'funcionario'];

if ($id > 0) {
    $usuario = new Usuario();
    $dados = $usuario->buscarPorId($id);

    if (!$dados) {
        header('Location: index.php');
        exit;
    }
}

$erros = [
    'dados' => 'Confira o nome, o e-mail e o tipo.',
    'senha' => 'A senha precisa ter pelo menos 6 caracteres.',
    'confirmar' => 'As senhas não são iguais.',
    'email' => 'Já existe um usuário com esse e-mail.',
    'proprio_tipo' => 'Você não pode tirar o seu próprio acesso de administrador.',
];
$erro = $erros[$_GET['erro'] ?? ''] ?? null;

$titulo = $id ? 'Editar usuário' : 'Novo usuário';
require __DIR__ . '/../topo.php';
?>

<h2><?= $titulo ?></h2>

<?php if ($erro): ?>
    <div class="msg erro"><?= $erro ?></div>
<?php endif; ?>

<div class="caixa form">
    <form action="<?= BASE_URL ?>controllers/UsuarioController.php?acao=salvar" method="POST">
        <input type="hidden" name="id" value="<?= $dados['id'] ?>">

        <label for="nome">Nome</label>
        <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($dados['nome']) ?>" required>

        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($dados['email']) ?>" required>

        <label for="tipo">Tipo</label>
        <select name="tipo" id="tipo">
            <option value="funcionario" <?= $dados['tipo'] === 'funcionario' ? 'selected' : '' ?>>Funcionário</option>
            <option value="admin" <?= $dados['tipo'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
        </select>

        <div class="linha">
            <div>
                <label for="senha">Senha<?= $id ? ' (deixe vazio para manter)' : '' ?></label>
                <input type="password" name="senha" id="senha" minlength="6" <?= $id ? '' : 'required' ?>>
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

<?php require __DIR__ . '/../rodape.php'; ?>
