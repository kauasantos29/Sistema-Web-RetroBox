<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/Pedido.php';

if (!clienteLogado()) {
    header('Location: ' . BASE_URL . 'views/loja/entrar.php');
    exit;
}

$pedido = new Pedido();
$pedidos = $pedido->listarPorCliente($_SESSION['cliente_id']);

$titulo = 'Meus pedidos - RetroBox';
require __DIR__ . '/topo.php';
?>

<main class="container">
    <h2>Meus pedidos</h2>

    <?php if (($_GET['msg'] ?? '') === 'finalizado'): ?>
        <div class="msg ok">Pedido #<?= (int) $_GET['id'] ?> realizado com sucesso!</div>
    <?php endif; ?>

    <?php foreach ($pedidos as $p): ?>
        <div class="caixa pedido">
            <div class="barra">
                <strong>Pedido #<?= $p['id'] ?></strong>
                <span class="status <?= strtolower($p['status']) ?>"><?= $p['status'] ?></span>
            </div>
            <small><?= date('d/m/Y H:i', strtotime($p['data_pedido'])) ?></small>

            <ul>
                <?php foreach ($pedido->listarItens($p['id']) as $item): ?>
                    <li><?= $item['quantidade'] ?>x <?= htmlspecialchars($item['titulo']) ?> - R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></li>
                <?php endforeach; ?>
            </ul>

            <strong>Total: R$ <?= number_format($p['total'], 2, ',', '.') ?></strong>
        </div>
    <?php endforeach; ?>

    <?php if (!$pedidos): ?>
        <p>Você ainda não fez nenhum pedido.</p>
        <a class="btn" href="<?= BASE_URL ?>">Ver jogos</a>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/rodape.php'; ?>
