<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../models/Pedido.php';
exigirLogin();

$status = in_array($_GET['status'] ?? '', Pedido::STATUS) ? $_GET['status'] : '';
$pedido = new Pedido();
$pedidos = $pedido->listar($status);

$mensagens = [
    'excluido' => ['ok', 'Pedido excluído com sucesso.'],
    'nao_cancelado' => ['erro', 'Só é possível excluir pedidos cancelados.'],
];
$msg = $mensagens[$_GET['msg'] ?? ''] ?? null;

$titulo = 'Pedidos';
require __DIR__ . '/../topo.php';
?>

<div class="barra">
    <h2>Pedidos</h2>
</div>

<?php if ($msg): ?>
    <div class="msg <?= $msg[0] ?>"><?= $msg[1] ?></div>
<?php endif; ?>

<form class="filtros" method="GET">
    <select name="status">
        <option value="">Todos os status</option>
        <?php foreach (Pedido::STATUS as $s): ?>
            <option <?= $status === $s ? 'selected' : '' ?>><?= $s ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filtrar</button>
    <a class="voltar" href="index.php">Limpar</a>
</form>

<table>
    <tr>
        <th>Nº</th>
        <th>Cliente</th>
        <th>Data</th>
        <th>Total</th>
        <th>Status</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($pedidos as $p): ?>
        <tr>
            <td>#<?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['cliente']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($p['data_pedido'])) ?></td>
            <td>R$ <?= number_format($p['total'], 2, ',', '.') ?></td>
            <td><span class="status <?= strtolower($p['status']) ?>"><?= $p['status'] ?></span></td>
            <td class="acoes">
                <a href="ver.php?id=<?= $p['id'] ?>">Ver / editar</a>
                <?php if ($p['status'] === 'Cancelado'): ?>
                    <a class="excluir" href="<?= BASE_URL ?>controllers/PedidoController.php?acao=excluir&id=<?= $p['id'] ?>"
                       onclick="return confirm('Excluir o pedido #<?= $p['id'] ?>?')">Excluir</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$pedidos): ?>
        <tr><td colspan="6">Nenhum pedido encontrado.</td></tr>
    <?php endif; ?>
</table>

<?php require __DIR__ . '/../rodape.php'; ?>
