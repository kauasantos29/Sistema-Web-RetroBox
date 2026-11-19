<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../models/Usuario.php';
exigirAdmin();

$usuario = new Usuario();
$busca = trim($_GET['busca'] ?? '');
$usuarios = $busca !== '' ? $usuario->buscarPorNome($busca) : $usuario->listar();

$mensagens = [
    'cadastrado' => ['ok', 'Usuário cadastrado com sucesso.'],
    'editado' => ['ok', 'Usuário atualizado com sucesso.'],
    'excluido' => ['ok', 'Usuário excluído com sucesso.'],
    'proprio' => ['erro', 'Você não pode excluir a sua própria conta.'],
];
$msg = $mensagens[$_GET['msg'] ?? ''] ?? null;

$titulo = 'Usuários';
require __DIR__ . '/../topo.php';
?>

<div class="barra">
    <h2>Usuários</h2>
    <a class="btn" href="form.php">+ Novo usuário</a>
</div>

<?php if ($msg): ?>
    <div class="msg <?= $msg[0] ?>"><?= $msg[1] ?></div>
<?php endif; ?>

<form class="filtros" method="GET">
    <input type="text" name="busca" placeholder="Buscar pelo nome" value="<?= htmlspecialchars($busca) ?>">
    <button type="submit">Buscar</button>
    <a class="voltar" href="index.php">Limpar</a>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>E-mail</th>
        <th>Tipo</th>
        <th>Cadastro</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nome']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['tipo'] === 'admin' ? 'Administrador' : 'Funcionário' ?></td>
            <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
            <td class="acoes">
                <a href="form.php?id=<?= $u['id'] ?>">Editar</a>
                <?php if ($u['id'] != $_SESSION['usuario_id']): ?>
                    <a class="excluir" href="<?= BASE_URL ?>controllers/UsuarioController.php?acao=excluir&id=<?= $u['id'] ?>"
                       onclick="return confirm('Excluir o usuário <?= htmlspecialchars($u['nome'], ENT_QUOTES) ?>?')">Excluir</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$usuarios): ?>
        <tr><td colspan="6">Nenhum usuário encontrado.</td></tr>
    <?php endif; ?>
</table>

<?php require __DIR__ . '/../rodape.php'; ?>
