<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Pedido.php';

exigirLogin();

$lista = BASE_URL . 'views/admin/pedidos/index.php';
$pedido = new Pedido();
$acao = $_GET['acao'] ?? '';

if ($acao === 'status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $novo = $_POST['status'];
    $atual = $pedido->buscarPorId($id);
    $pagina = BASE_URL . 'views/admin/pedidos/ver.php?id=' . $id;

    if (!$atual || !in_array($novo, Pedido::STATUS)) {
        header('Location: ' . $lista);
        exit;
    }

    // pedido cancelado não volta a ficar ativo
    if ($atual['status'] === 'Cancelado') {
        header('Location: ' . $pagina . '&msg=cancelado');
        exit;
    }

    if ($novo === 'Cancelado') {
        $pedido->devolverEstoque($id);
    }

    $pedido->id = $id;
    $pedido->status = $novo;
    $pedido->editar();

    header('Location: ' . $pagina . '&msg=status');
    exit;
}

if ($acao === 'excluir') {
    $id = (int) ($_GET['id'] ?? 0);
    $dados = $pedido->buscarPorId($id);

    // só exclui pedido cancelado, assim o estoque já foi devolvido
    if (!$dados || $dados['status'] !== 'Cancelado') {
        header('Location: ' . $lista . '?msg=nao_cancelado');
        exit;
    }

    $pedido->id = $id;
    $pedido->excluir();
    header('Location: ' . $lista . '?msg=excluido');
    exit;
}

header('Location: ' . $lista);
