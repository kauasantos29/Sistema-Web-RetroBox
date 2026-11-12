<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Usuario.php';

$acao = $_GET['acao'] ?? '';

if ($acao === 'sair') {
    session_destroy();
    header('Location: ' . BASE_URL . 'views/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario();
    $dados = $usuario->autenticar(trim($_POST['email']), $_POST['senha']);

    if ($dados) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $dados['id'];
        $_SESSION['usuario_nome'] = $dados['nome'];
        $_SESSION['usuario_tipo'] = $dados['tipo'];

        header('Location: ' . BASE_URL . 'views/admin/painel.php');
        exit;
    }

    header('Location: ' . BASE_URL . 'views/login.php?erro=1');
    exit;
}

header('Location: ' . BASE_URL . 'views/login.php');
