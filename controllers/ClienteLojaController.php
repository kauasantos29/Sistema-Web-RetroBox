<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Cliente.php';

$cliente = new Cliente();
$acao = $_GET['acao'] ?? '';
$voltar = ($_POST['voltar'] ?? '') === 'carrinho' ? 'views/loja/carrinho.php' : '';

function entrarComo($dados)
{
    session_regenerate_id(true);
    $_SESSION['cliente_id'] = $dados['id'];
    $_SESSION['cliente_nome'] = $dados['nome'];
}

if ($acao === 'cadastrar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $senha = $_POST['senha'];
    $pagina = BASE_URL . 'views/loja/cadastro.php?erro=';

    if ($nome === '' || $telefone === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ' . $pagina . 'dados');
        exit;
    }
    if (strlen($senha) < 6) {
        header('Location: ' . $pagina . 'senha');
        exit;
    }
    if ($senha !== $_POST['confirmar_senha']) {
        header('Location: ' . $pagina . 'confirmar');
        exit;
    }
    if ($cliente->emailExiste($email)) {
        header('Location: ' . $pagina . 'email');
        exit;
    }

    $cliente->nome = $nome;
    $cliente->email = $email;
    $cliente->telefone = $telefone;
    $cliente->senha = $senha;
    $cliente->cadastrar();

    entrarComo($cliente->autenticar($email, $senha));
    header('Location: ' . BASE_URL . $voltar);
    exit;
}

if ($acao === 'entrar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = $cliente->autenticar(trim($_POST['email']), $_POST['senha']);

    if (!$dados) {
        $extra = $voltar ? '&voltar=carrinho' : '';
        header('Location: ' . BASE_URL . 'views/loja/entrar.php?erro=1' . $extra);
        exit;
    }

    entrarComo($dados);
    header('Location: ' . BASE_URL . $voltar);
    exit;
}

if ($acao === 'sair') {
    unset($_SESSION['cliente_id'], $_SESSION['cliente_nome']);
}

header('Location: ' . BASE_URL);
