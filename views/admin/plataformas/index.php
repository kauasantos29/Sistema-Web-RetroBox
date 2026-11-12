<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../models/Plataforma.php';
exigirLogin();

$plataforma = new Plataforma();
$plataformas = $plataforma->listar();

$mensagens = [
    'cadastrado' => ['ok', 'Plataforma cadastrada com sucesso.'],
    'editado' => ['ok', 'Plataforma atualizada com sucesso.'],
    'excluido' => ['ok', 'Plataforma excluída com sucesso.'],
    'tem_jogos' => ['erro', 'Não é possível excluir: existem jogos cadastrados nessa plataforma.'],
];
$msg = $mensagens[$_GET['msg'] ?? ''] ?? null;

$titulo = 'Plataformas';
require __DIR__ . '/../topo.php';
?>

<div class="barra">
    <h2>Plataformas</h2>
    <a class="btn" href="form.php">+ Nova plataforma</a>
</div>

<?php if ($msg): ?>
    <div class="msg <?= $msg[0] ?>"><?= $msg[1] ?></div>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Fabricante</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($plataformas as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['nome']) ?></td>
            <td><?= htmlspecialchars($p['fabricante']) ?></td>
            <td class="acoes">
                <a href="form.php?id=<?= $p['id'] ?>">Editar</a>
                <a class="excluir" href="<?= BASE_URL ?>controllers/PlataformaController.php?acao=excluir&id=<?= $p['id'] ?>"
                   onclick="return confirm('Excluir a plataforma <?= htmlspecialchars($p['nome'], ENT_QUOTES) ?>?')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$plataformas): ?>
        <tr><td colspan="4">Nenhuma plataforma cadastrada.</td></tr>
    <?php endif; ?>
</table>

<?php require __DIR__ . '/../rodape.php'; ?>
