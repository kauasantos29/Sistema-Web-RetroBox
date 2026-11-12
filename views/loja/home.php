<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../models/Jogo.php';
require_once __DIR__ . '/../../models/Plataforma.php';

$jogo = new Jogo();
$plataforma = new Plataforma();
$plataformas = $plataforma->listar();

$busca = trim($_GET['busca'] ?? '');
$filtro = (int) ($_GET['plataforma'] ?? 0);

if ($busca !== '') {
    $jogos = $jogo->buscarPorNome($busca);
} elseif ($filtro > 0) {
    $jogos = $jogo->filtrarPorPlataforma($filtro);
} else {
    $jogos = $jogo->listar();
}

$titulo = 'RetroBox - Loja de games retrô';
require __DIR__ . '/topo.php';
?>

<section class="banner">
    <h1>Os clássicos que marcaram época</h1>
    <p>Jogos originais de Super Nintendo, Mega Drive, PlayStation e muito mais. Lacrados, completos ou só o cartucho.</p>
</section>

<main class="container">
    <div class="plataformas">
        <a href="<?= BASE_URL ?>" class="<?= !$filtro && !$busca ? 'ativa' : '' ?>">Todos</a>
        <?php foreach ($plataformas as $p): ?>
            <a href="?plataforma=<?= $p['id'] ?>" class="<?= $filtro == $p['id'] ? 'ativa' : '' ?>"><?= htmlspecialchars($p['nome']) ?></a>
        <?php endforeach; ?>
    </div>

    <form class="filtros" method="GET">
        <input type="text" name="busca" placeholder="Buscar jogo" value="<?= htmlspecialchars($busca) ?>">
        <button type="submit">Buscar</button>
    </form>

    <?php if ($jogos): ?>
        <div class="grade">
            <?php foreach ($jogos as $j): ?>
                <div class="card">
                    <?php if ($j['imagem']): ?>
                        <img src="<?= BASE_URL ?>public/img/jogos/<?= htmlspecialchars($j['imagem']) ?>" alt="<?= htmlspecialchars($j['titulo']) ?>">
                    <?php else: ?>
                        <div class="sem-foto">Sem imagem</div>
                    <?php endif; ?>

                    <div class="info">
                        <span class="plataforma"><?= htmlspecialchars($j['plataforma']) ?></span>
                        <h3><?= htmlspecialchars($j['titulo']) ?></h3>
                        <p class="detalhe">
                            <?= $j['ano_lancamento'] ? 'Lançado em ' . $j['ano_lancamento'] . ' · ' : '' ?><?= $j['conservacao'] ?>
                        </p>
                        <span class="preco">R$ <?= number_format($j['preco'], 2, ',', '.') ?></span>
                        <?php if ($j['estoque'] > 0): ?>
                            <span class="estoque"><?= $j['estoque'] ?> em estoque</span>
                        <?php else: ?>
                            <span class="estoque esgotado">Esgotado</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Nenhum jogo encontrado.</p>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/rodape.php'; ?>
