<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Carrinho.php';
require_once __DIR__ . '/../models/Pedido.php';

$carrinho = new Carrinho();
$acao = $_GET['acao'] ?? '';
$id = (int) ($_POST['id'] ?? 0);
$paginaCarrinho = BASE_URL . 'views/loja/carrinho.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $paginaCarrinho);
    exit;
}

if ($acao === 'adicionar') {
    $qtd = (int) ($_POST['quantidade'] ?? 1);

    if ($carrinho->adicionar($id, $qtd)) {
        header('Location: ' . $paginaCarrinho . '?msg=adicionado');
    } else {
        header('Location: ' . BASE_URL . 'views/loja/jogo.php?id=' . $id . '&erro=estoque');
    }
    exit;
}

if ($acao === 'atualizar') {
    $carrinho->atualizar($id, (int) $_POST['quantidade']);
}

if ($acao === 'remover') {
    $carrinho->remover($id);
}

if ($acao === 'limpar') {
    $carrinho->limpar();
}

if ($acao === 'finalizar') {
    if (!clienteLogado()) {
        header('Location: ' . BASE_URL . 'views/loja/entrar.php?voltar=carrinho');
        exit;
    }

    $itens = $carrinho->itens();
    if (!$itens) {
        header('Location: ' . $paginaCarrinho);
        exit;
    }

    $pedido = new Pedido();
    $pedidoId = $pedido->finalizar($_SESSION['cliente_id'], $itens);

    if (!$pedidoId) {
        header('Location: ' . $paginaCarrinho . '?msg=sem_estoque');
        exit;
    }

    $carrinho->limpar();
    header('Location: ' . BASE_URL . 'views/loja/pedidos.php?msg=finalizado&id=' . $pedidoId);
    exit;
}

header('Location: ' . $paginaCarrinho);
