<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../models/Pedido.php';
exigirLogin();

$pedido = new Pedido();
$dados = $pedido->buscarPorId((int) ($_GET['id'] ?? 0));

if (!$dados) {
    header('Location: index.php');
    exit;
}

$itens = $pedido->listarItens($dados['id']);

$mensagens = [
    'status' => ['ok', 'Status atualizado com sucesso.'],
    'cancelado' => ['erro', 'Esse pedido foi cancelado e não pode mais ser alterado.'],
];
$msg = $mensagens[$_GET['msg'] ?? ''] ?? null;

$titulo = 'Pedido #' . $dados['id'];
require __DIR__ . '/../topo.php';
?>

<div class="barra">
    <h2>Pedido #<?= $dados['id'] ?></h2>
    <a class="voltar" href="index.php">&larr; Voltar</a>
</div>

<?php if ($msg): ?>
    <div class="msg <?= $msg[0] ?>"><?= $msg[1] ?></div>
<?php endif; ?>

<div class="caixa">
    <p>
        <strong>Cliente:</strong> <?= htmlspecialchars($dados['cliente']) ?><br>
        <strong>E-mail:</strong> <?= htmlspecialchars($dados['email']) ?><br>
        <strong>Telefone:</strong> <?= htmlspecialchars($dados['telefone']) ?><br>
        <strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($dados['data_pedido'])) ?>
    </p>

    <table>
        <tr>
            <th>Jogo</th>
            <th>Preço unitário</th>
            <th>Quantidade</th>
            <th>Subtotal</th>
        </tr>
        <?php foreach ($itens as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['titulo']) ?></td>
                <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                <td><?= $item['quantidade'] ?></td>
                <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th colspan="3">Total</th>
            <th>R$ <?= number_format($dados['total'], 2, ',', '.') ?></th>
        </tr>
    </table>

    <?php if ($dados['status'] === 'Cancelado'): ?>
        <p><span class="status cancelado">Cancelado</span> O estoque desse pedido já foi devolvido.</p>
    <?php else: ?>
        <form class="filtros status-form" action="<?= BASE_URL ?>controllers/PedidoController.php?acao=status" method="POST"
              onsubmit="return this.status.value !== 'Cancelado' || confirm('Cancelar o pedido? O estoque dos jogos será devolvido.')">
            <input type="hidden" name="id" value="<?= $dados['id'] ?>">
            <label for="status">Status</label>
            <select name="status" id="status">
                <?php foreach (Pedido::STATUS as $s): ?>
                    <option <?= $dados['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Salvar status</button>
        </form>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../rodape.php'; ?>
