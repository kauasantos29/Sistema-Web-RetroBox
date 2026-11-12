<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Cliente.php';

exigirLogin();

$lista = BASE_URL . 'views/admin/clientes/index.php';
$cliente = new Cliente();
$acao = $_GET['acao'] ?? '';

function voltarComErro($id, $erro)
{
    header('Location: ' . BASE_URL . 'views/admin/clientes/form.php?id=' . $id . '&erro=' . $erro);
    exit;
}

if ($acao === 'salvar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $senha = $_POST['senha'];

    if ($nome === '' || $telefone === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        voltarComErro($id, 'dados');
    }

    // no cadastro a senha é obrigatória, na edição só se for trocar
    if (($id === 0 || $senha !== '') && strlen($senha) < 6) {
        voltarComErro($id, 'senha');
    }

    if ($senha !== $_POST['confirmar_senha']) {
        voltarComErro($id, 'confirmar');
    }

    if ($cliente->emailExiste($email, $id)) {
        voltarComErro($id, 'email');
    }

    $cliente->nome = $nome;
    $cliente->email = $email;
    $cliente->telefone = $telefone;
    $cliente->senha = $senha;

    if ($id > 0) {
        $cliente->id = $id;
        $cliente->editar();
        header('Location: ' . $lista . '?msg=editado');
    } else {
        $cliente->cadastrar();
        header('Location: ' . $lista . '?msg=cadastrado');
    }
    exit;
}

if ($acao === 'excluir') {
    $id = (int) ($_GET['id'] ?? 0);

    if ($cliente->possuiPedidos($id)) {
        header('Location: ' . $lista . '?msg=tem_pedidos');
        exit;
    }

    $cliente->id = $id;
    $cliente->excluir();
    header('Location: ' . $lista . '?msg=excluido');
    exit;
}

header('Location: ' . $lista);
