<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/Carrinho.php';

$carrinho = new Carrinho();
$itens = $carrinho->itens();

$mensagens = [
    'adicionado' => ['ok', 'Jogo adicionado ao carrinho.'],
    'sem_estoque' => ['erro', 'Algum jogo do carrinho ficou sem estoque. Confira as quantidades.'],
];
$msg = $mensagens[$_GET['msg'] ?? ''] ?? null;
$acao = BASE_URL . 'controllers/CarrinhoController.php?acao=';

$titulo = 'Carrinho - RetroBox';
require __DIR__ . '/topo.php';
?>

<main class="container">
    <h2>Meu carrinho</h2>

    <?php if ($msg): ?>
        <div class="msg <?= $msg[0] ?>"><?= $msg[1] ?></div>
    <?php endif; ?>

    <?php if ($itens): ?>
        <table>
            <tr>
                <th>Jogo</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
            <?php foreach ($itens as $item): ?>
                <tr>
                    <td>
                        <a href="jogo.php?id=<?= $item['id'] ?>"><?= htmlspecialchars($item['titulo']) ?></a>
                        <small>(<?= htmlspecialchars($item['plataforma']) ?>)</small>
                    </td>
                    <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                    <td>
                        <form class="qtd" action="<?= $acao ?>atualizar" method="POST">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <input type="number" name="quantidade" value="<?= $item['quantidade'] ?>" min="1" max="<?= $item['estoque'] ?>">
                            <button type="submit" class="btn-link">Atualizar</button>
                        </form>
                    </td>
                    <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                    <td>
                        <form action="<?= $acao ?>remover" method="POST">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn-link excluir">Remover</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="total">
            <form action="<?= $acao ?>limpar" method="POST" onsubmit="return confirm('Esvaziar o carrinho?')">
                <button type="submit" class="btn-link excluir">Esvaziar carrinho</button>
            </form>
            <strong>Total: R$ <?= number_format($carrinho->total(), 2, ',', '.') ?></strong>
        </div>

        <div class="finalizar">
            <a href="<?= BASE_URL ?>">Continuar comprando</a>
            <form action="<?= $acao ?>finalizar" method="POST">
                <button type="submit"><?= clienteLogado() ? 'Finalizar pedido' : 'Entrar para finalizar' ?></button>
            </form>
        </div>
    <?php else: ?>
        <p>Seu carrinho está vazio.</p>
        <a class="btn" href="<?= BASE_URL ?>">Ver jogos</a>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/rodape.php'; ?>
