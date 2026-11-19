<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/Jogo.php';

$jogo = new Jogo();
$dados = $jogo->buscarPorId((int) ($_GET['id'] ?? 0));

if (!$dados) {
    header('Location: ' . BASE_URL);
    exit;
}

$titulo = $dados['titulo'] . ' - RetroBox';
require __DIR__ . '/topo.php';
?>

<main class="container">
    <a class="voltar-loja" href="<?= BASE_URL ?>">&larr; Voltar para a loja</a>

    <?php if (isset($_GET['erro'])): ?>
        <div class="msg erro">Não há estoque suficiente para essa quantidade.</div>
    <?php endif; ?>

    <div class="detalhes">
        <div class="foto">
            <?php if ($dados['imagem']): ?>
                <img src="<?= BASE_URL ?>public/img/jogos/<?= htmlspecialchars($dados['imagem']) ?>" alt="<?= htmlspecialchars($dados['titulo']) ?>">
            <?php else: ?>
                <div class="sem-foto">Sem imagem</div>
            <?php endif; ?>
        </div>

        <div class="caixa">
            <span class="plataforma"><?= htmlspecialchars($dados['plataforma']) ?></span>
            <h1><?= htmlspecialchars($dados['titulo']) ?></h1>

            <table class="ficha">
                <tr><th>Plataforma</th><td><?= htmlspecialchars($dados['plataforma']) ?></td></tr>
                <tr><th>Lançamento</th><td><?= $dados['ano_lancamento'] ?: '-' ?></td></tr>
                <tr><th>Conservação</th><td><?= $dados['conservacao'] ?></td></tr>
                <tr><th>Estoque</th><td><?= $dados['estoque'] > 0 ? $dados['estoque'] . ' unidade(s)' : 'Esgotado' ?></td></tr>
            </table>

            <?php if ($dados['descricao']): ?>
                <p><?= nl2br(htmlspecialchars($dados['descricao'])) ?></p>
            <?php endif; ?>

            <span class="preco">R$ <?= number_format($dados['preco'], 2, ',', '.') ?></span>

            <?php if ($dados['estoque'] > 0): ?>
                <form class="comprar" action="<?= BASE_URL ?>controllers/CarrinhoController.php?acao=adicionar" method="POST">
                    <input type="hidden" name="id" value="<?= $dados['id'] ?>">
                    <input type="number" name="quantidade" value="1" min="1" max="<?= $dados['estoque'] ?>">
                    <button type="submit">Adicionar ao carrinho</button>
                </form>
            <?php else: ?>
                <button disabled>Esgotado</button>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require __DIR__ . '/rodape.php'; ?>
