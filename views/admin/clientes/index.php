<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../models/Cliente.php';
exigirLogin();

$cliente = new Cliente();
$busca = trim($_GET['busca'] ?? '');
$clientes = $busca !== '' ? $cliente->buscarPorNome($busca) : $cliente->listar();

$mensagens = [
    'cadastrado' => ['ok', 'Cliente cadastrado com sucesso.'],
    'editado' => ['ok', 'Cliente atualizado com sucesso.'],
    'excluido' => ['ok', 'Cliente excluído com sucesso.'],
    'tem_pedidos' => ['erro', 'Não é possível excluir: esse cliente já fez pedidos.'],
];
$msg = $mensagens[$_GET['msg'] ?? ''] ?? null;

$titulo = 'Clientes';
require __DIR__ . '/../topo.php';
?>

<div class="barra">
    <h2>Clientes</h2>
    <a class="btn" href="form.php">+ Novo cliente</a>
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
        <th>Telefone</th>
        <th>Cadastro</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($clientes as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['nome']) ?></td>
            <td><?= htmlspecialchars($c['email']) ?></td>
            <td><?= htmlspecialchars($c['telefone']) ?></td>
            <td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
            <td class="acoes">
                <a href="form.php?id=<?= $c['id'] ?>">Editar</a>
                <a class="excluir" href="<?= BASE_URL ?>controllers/ClienteController.php?acao=excluir&id=<?= $c['id'] ?>"
                   onclick="return confirm('Excluir o cliente <?= htmlspecialchars($c['nome'], ENT_QUOTES) ?>?')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$clientes): ?>
        <tr><td colspan="6">Nenhum cliente encontrado.</td></tr>
    <?php endif; ?>
</table>

<?php require __DIR__ . '/../rodape.php'; ?>
