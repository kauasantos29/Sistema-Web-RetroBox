<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/Jogo.php';
require_once __DIR__ . '/../../models/Cliente.php';
require_once __DIR__ . '/../../models/Pedido.php';
exigirLogin();

$jogo = new Jogo();
$cliente = new Cliente();
$pedido = new Pedido();

$ultimos = $pedido->ultimos(5);
$estoqueBaixo = $jogo->estoqueBaixo();

$titulo = 'Painel';
require __DIR__ . '/topo.php';
?>

<h2>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h2>

<div class="resumo">
    <div class="caixa">
        <span>Jogos</span>
        <strong><?= $jogo->contar() ?></strong>
    </div>
    <div class="caixa">
        <span>Clientes</span>
        <strong><?= $cliente->contar() ?></strong>
    </div>
    <div class="caixa">
        <span>Pedidos</span>
        <strong><?= $pedido->contar() ?></strong>
    </div>
    <div class="caixa">
        <span>Total vendido</span>
        <strong>R$ <?= number_format($pedido->totalVendido(), 2, ',', '.') ?></strong>
    </div>
</div>

<div class="painel-colunas">
    <div>
        <h3>Últimos pedidos</h3>
        <table>
            <tr>
                <th>Nº</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
            <?php foreach ($ultimos as $p): ?>
                <tr>
                    <td><a href="pedidos/ver.php?id=<?= $p['id'] ?>">#<?= $p['id'] ?></a></td>
                    <td><?= htmlspecialchars($p['cliente']) ?></td>
                    <td>R$ <?= number_format($p['total'], 2, ',', '.') ?></td>
                    <td><span class="status <?= strtolower($p['status']) ?>"><?= $p['status'] ?></span></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$ultimos): ?>
                <tr><td colspan="4">Nenhum pedido ainda.</td></tr>
            <?php endif; ?>
        </table>
    </div>

    <div>
        <h3>Estoque baixo</h3>
        <table>
            <tr>
                <th>Jogo</th>
                <th>Estoque</th>
            </tr>
            <?php foreach ($estoqueBaixo as $j): ?>
                <tr>
                    <td><a href="jogos/form.php?id=<?= $j['id'] ?>"><?= htmlspecialchars($j['titulo']) ?></a></td>
                    <td><?= $j['estoque'] == 0 ? '<span class="status cancelado">Esgotado</span>' : $j['estoque'] ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$estoqueBaixo): ?>
                <tr><td colspan="2">Nenhum jogo com estoque baixo.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<?php require __DIR__ . '/rodape.php'; ?>
