<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Plataforma.php';

exigirLogin();

$lista = BASE_URL . 'views/admin/plataformas/index.php';
$plataforma = new Plataforma();
$acao = $_GET['acao'] ?? '';

if ($acao === 'salvar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $nome = trim($_POST['nome']);
    $fabricante = trim($_POST['fabricante']);

    if ($nome === '' || $fabricante === '') {
        header('Location: ' . BASE_URL . 'views/admin/plataformas/form.php?id=' . $id . '&erro=vazio');
        exit;
    }

    if ($plataforma->nomeExiste($nome, $id)) {
        header('Location: ' . BASE_URL . 'views/admin/plataformas/form.php?id=' . $id . '&erro=duplicado');
        exit;
    }

    $plataforma->nome = $nome;
    $plataforma->fabricante = $fabricante;

    if ($id > 0) {
        $plataforma->id = $id;
        $plataforma->editar();
        header('Location: ' . $lista . '?msg=editado');
    } else {
        $plataforma->cadastrar();
        header('Location: ' . $lista . '?msg=cadastrado');
    }
    exit;
}

if ($acao === 'excluir') {
    $id = (int) ($_GET['id'] ?? 0);

    if ($plataforma->possuiJogos($id)) {
        header('Location: ' . $lista . '?msg=tem_jogos');
        exit;
    }

    $plataforma->id = $id;
    $plataforma->excluir();
    header('Location: ' . $lista . '?msg=excluido');
    exit;
}

header('Location: ' . $lista);
