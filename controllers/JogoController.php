<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Jogo.php';
require_once __DIR__ . '/../models/Plataforma.php';

exigirLogin();

$lista = BASE_URL . 'views/admin/jogos/index.php';
$pastaImagens = __DIR__ . '/../public/img/jogos/';
$jogo = new Jogo();
$acao = $_GET['acao'] ?? '';

function voltarComErro($id, $erro)
{
    header('Location: ' . BASE_URL . 'views/admin/jogos/form.php?id=' . $id . '&erro=' . $erro);
    exit;
}

if ($acao === 'salvar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $titulo = trim($_POST['titulo']);
    $preco = (float) str_replace(',', '.', $_POST['preco']);
    $estoque = (int) $_POST['estoque'];
    $plataformaId = (int) $_POST['plataforma_id'];
    $conservacao = $_POST['conservacao'];

    if ($titulo === '' || $preco < 0 || $estoque < 0) {
        voltarComErro($id, 'dados');
    }

    $plataforma = new Plataforma();
    if (!$plataforma->buscarPorId($plataformaId) || !in_array($conservacao, Jogo::CONSERVACOES)) {
        voltarComErro($id, 'dados');
    }

    $atual = $id > 0 ? $jogo->buscarPorId($id) : null;
    $imagem = $atual['imagem'] ?? null;

    if (!empty($_FILES['imagem']['name'])) {
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));

        if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            voltarComErro($id, 'imagem');
        }
        if ($_FILES['imagem']['size'] > 2 * 1024 * 1024) {
            voltarComErro($id, 'tamanho');
        }

        if (!is_dir($pastaImagens)) {
            mkdir($pastaImagens, 0777, true);
        }

        $novoNome = uniqid('jogo_') . '.' . $extensao;
        if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $pastaImagens . $novoNome)) {
            voltarComErro($id, 'upload');
        }

        if ($imagem && file_exists($pastaImagens . $imagem)) {
            unlink($pastaImagens . $imagem);
        }
        $imagem = $novoNome;
    }

    $jogo->titulo = $titulo;
    $jogo->descricao = trim($_POST['descricao']);
    $jogo->preco = $preco;
    $jogo->estoque = $estoque;
    $jogo->ano_lancamento = $_POST['ano_lancamento'] !== '' ? (int) $_POST['ano_lancamento'] : null;
    $jogo->conservacao = $conservacao;
    $jogo->imagem = $imagem;
    $jogo->plataforma_id = $plataformaId;

    if ($id > 0) {
        $jogo->id = $id;
        $jogo->editar();
        header('Location: ' . $lista . '?msg=editado');
    } else {
        $jogo->cadastrar();
        header('Location: ' . $lista . '?msg=cadastrado');
    }
    exit;
}

if ($acao === 'excluir') {
    $id = (int) ($_GET['id'] ?? 0);

    if ($jogo->foiVendido($id)) {
        header('Location: ' . $lista . '?msg=vendido');
        exit;
    }

    $dados = $jogo->buscarPorId($id);
    $jogo->id = $id;
    $jogo->excluir();

    if ($dados && $dados['imagem'] && file_exists($pastaImagens . $dados['imagem'])) {
        unlink($pastaImagens . $dados['imagem']);
    }

    header('Location: ' . $lista . '?msg=excluido');
    exit;
}

header('Location: ' . $lista);
