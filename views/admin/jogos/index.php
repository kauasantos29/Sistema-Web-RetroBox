<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../models/Jogo.php';
require_once __DIR__ . '/../../../models/Plataforma.php';
exigirLogin();

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

$mensagens = [
    'cadastrado' => ['ok', 'Jogo cadastrado com sucesso.'],
    'editado' => ['ok', 'Jogo atualizado com sucesso.'],
    'excluido' => ['ok', 'Jogo excluído com sucesso.'],
    'vendido' => ['erro', 'Não é possível excluir: esse jogo já faz parte de um pedido.'],
];
$msg = $mensagens[$_GET['msg'] ?? ''] ?? null;

$titulo = 'Jogos';
require __DIR__ . '/../topo.php';
?>

<div class="barra">
    <h2>Jogos</h2>
    <a class="btn" href="form.php">+ Novo jogo</a>
</div>

<?php if ($msg): ?>
    <div class="msg <?= $msg[0] ?>"><?= $msg[1] ?></div>
<?php endif; ?>

<form class="filtros" method="GET">
    <input type="text" name="busca" placeholder="Buscar pelo nome" value="<?= htmlspecialchars($busca) ?>">
    <select name="plataforma">
        <option value="0">Todas as plataformas</option>
        <?php foreach ($plataformas as $p): ?>
            <option value="<?= $p['id'] ?>" <?= $filtro == $p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['nome']) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filtrar</button>
    <a class="voltar" href="index.php">Limpar</a>
</form>

<table>
    <tr>
        <th>Imagem</th>
        <th>Título</th>
        <th>Plataforma</th>
        <th>Ano</th>
        <th>Conservação</th>
        <th>Preço</th>
        <th>Estoque</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($jogos as $j): ?>
        <tr>
            <td>
                <?php if ($j['imagem']): ?>
                    <img class="thumb" src="<?= BASE_URL ?>public/img/jogos/<?= htmlspecialchars($j['imagem']) ?>" alt="">
                <?php else: ?>
                    <span class="sem-imagem">-</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($j['titulo']) ?></td>
            <td><?= htmlspecialchars($j['plataforma']) ?></td>
            <td><?= $j['ano_lancamento'] ?></td>
            <td><?= $j['conservacao'] ?></td>
            <td>R$ <?= number_format($j['preco'], 2, ',', '.') ?></td>
            <td><?= $j['estoque'] ?></td>
            <td class="acoes">
                <a href="form.php?id=<?= $j['id'] ?>">Editar</a>
                <a class="excluir" href="<?= BASE_URL ?>controllers/JogoController.php?acao=excluir&id=<?= $j['id'] ?>"
                   onclick="return confirm('Excluir o jogo <?= htmlspecialchars($j['titulo'], ENT_QUOTES) ?>?')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$jogos): ?>
        <tr><td colspan="8">Nenhum jogo encontrado.</td></tr>
    <?php endif; ?>
</table>

<?php require __DIR__ . '/../rodape.php'; ?>
