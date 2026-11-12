<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../models/Jogo.php';
require_once __DIR__ . '/../../../models/Plataforma.php';
exigirLogin();

$plataforma = new Plataforma();
$plataformas = $plataforma->listar();

$id = (int) ($_GET['id'] ?? 0);
$dados = [
    'id' => 0, 'titulo' => '', 'descricao' => '', 'preco' => '', 'estoque' => 0,
    'ano_lancamento' => '', 'conservacao' => 'Completo', 'imagem' => null, 'plataforma_id' => 0,
];

if ($id > 0) {
    $jogo = new Jogo();
    $dados = $jogo->buscarPorId($id);

    if (!$dados) {
        header('Location: index.php');
        exit;
    }
}

$erros = [
    'dados' => 'Confira os dados do formulário.',
    'imagem' => 'A imagem precisa ser JPG, PNG, WEBP ou GIF.',
    'tamanho' => 'A imagem pode ter no máximo 2 MB.',
    'upload' => 'Não foi possível enviar a imagem.',
];
$erro = $erros[$_GET['erro'] ?? ''] ?? null;

$titulo = $id ? 'Editar jogo' : 'Novo jogo';
require __DIR__ . '/../topo.php';
?>

<h2><?= $titulo ?></h2>

<?php if ($erro): ?>
    <div class="msg erro"><?= $erro ?></div>
<?php endif; ?>

<?php if (!$plataformas): ?>
    <div class="msg erro">Cadastre uma plataforma antes de cadastrar jogos.</div>
<?php endif; ?>

<div class="caixa form">
    <form action="<?= BASE_URL ?>controllers/JogoController.php?acao=salvar" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $dados['id'] ?>">

        <label for="titulo">Título</label>
        <input type="text" name="titulo" id="titulo" value="<?= htmlspecialchars($dados['titulo']) ?>" required>

        <label for="plataforma_id">Plataforma</label>
        <select name="plataforma_id" id="plataforma_id" required>
            <option value="">Selecione</option>
            <?php foreach ($plataformas as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $dados['plataforma_id'] == $p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['nome']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="descricao">Descrição</label>
        <textarea name="descricao" id="descricao" rows="3"><?= htmlspecialchars($dados['descricao']) ?></textarea>

        <div class="linha">
            <div>
                <label for="preco">Preço (R$)</label>
                <input type="number" name="preco" id="preco" step="0.01" min="0" value="<?= $dados['preco'] ?>" required>
            </div>
            <div>
                <label for="estoque">Estoque</label>
                <input type="number" name="estoque" id="estoque" min="0" value="<?= $dados['estoque'] ?>" required>
            </div>
        </div>

        <div class="linha">
            <div>
                <label for="ano_lancamento">Ano de lançamento</label>
                <input type="number" name="ano_lancamento" id="ano_lancamento" min="1970" max="2010" value="<?= $dados['ano_lancamento'] ?>">
            </div>
            <div>
                <label for="conservacao">Conservação</label>
                <select name="conservacao" id="conservacao">
                    <?php foreach (Jogo::CONSERVACOES as $c): ?>
                        <option <?= $dados['conservacao'] === $c ? 'selected' : '' ?>><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <label for="imagem">Imagem</label>
        <?php if ($dados['imagem']): ?>
            <img class="thumb grande" src="<?= BASE_URL ?>public/img/jogos/<?= htmlspecialchars($dados['imagem']) ?>" alt="">
        <?php endif; ?>
        <input type="file" name="imagem" id="imagem" accept="image/*">

        <button type="submit">Salvar</button>
        <a class="voltar" href="index.php">Cancelar</a>
    </form>
</div>

<?php require __DIR__ . '/../rodape.php'; ?>
