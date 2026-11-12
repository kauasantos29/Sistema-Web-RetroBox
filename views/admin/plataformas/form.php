<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../models/Plataforma.php';
exigirLogin();

$id = (int) ($_GET['id'] ?? 0);
$dados = ['id' => 0, 'nome' => '', 'fabricante' => ''];

if ($id > 0) {
    $plataforma = new Plataforma();
    $dados = $plataforma->buscarPorId($id);

    if (!$dados) {
        header('Location: index.php');
        exit;
    }
}

$erros = [
    'vazio' => 'Preencha todos os campos.',
    'duplicado' => 'Já existe uma plataforma com esse nome.',
];
$erro = $erros[$_GET['erro'] ?? ''] ?? null;

$titulo = $id ? 'Editar plataforma' : 'Nova plataforma';
require __DIR__ . '/../topo.php';
?>

<h2><?= $titulo ?></h2>

<?php if ($erro): ?>
    <div class="msg erro"><?= $erro ?></div>
<?php endif; ?>

<div class="caixa form">
    <form action="<?= BASE_URL ?>controllers/PlataformaController.php?acao=salvar" method="POST">
        <input type="hidden" name="id" value="<?= $dados['id'] ?>">

        <label for="nome">Nome</label>
        <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($dados['nome']) ?>" required>

        <label for="fabricante">Fabricante</label>
        <input type="text" name="fabricante" id="fabricante" value="<?= htmlspecialchars($dados['fabricante']) ?>" required>

        <button type="submit">Salvar</button>
        <a class="voltar" href="index.php">Cancelar</a>
    </form>
</div>

<?php require __DIR__ . '/../rodape.php'; ?>
