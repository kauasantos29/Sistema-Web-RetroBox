<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Usuario.php';

exigirAdmin();

$lista = BASE_URL . 'views/admin/usuarios/index.php';
$usuario = new Usuario();
$acao = $_GET['acao'] ?? '';

function voltarComErro($id, $erro)
{
    header('Location: ' . BASE_URL . 'views/admin/usuarios/form.php?id=' . $id . '&erro=' . $erro);
    exit;
}

if ($acao === 'salvar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $tipo = $_POST['tipo'];
    $senha = $_POST['senha'];

    if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($tipo, Usuario::TIPOS)) {
        voltarComErro($id, 'dados');
    }

    if (($id === 0 || $senha !== '') && strlen($senha) < 6) {
        voltarComErro($id, 'senha');
    }

    if ($senha !== $_POST['confirmar_senha']) {
        voltarComErro($id, 'confirmar');
    }

    if ($usuario->emailExiste($email, $id)) {
        voltarComErro($id, 'email');
    }

    // o admin logado não pode tirar o próprio acesso de admin
    if ($id === (int) $_SESSION['usuario_id'] && $tipo !== 'admin') {
        voltarComErro($id, 'proprio_tipo');
    }

    $usuario->nome = $nome;
    $usuario->email = $email;
    $usuario->tipo = $tipo;
    $usuario->senha = $senha;

    if ($id > 0) {
        $usuario->id = $id;
        $usuario->editar();

        if ($id === (int) $_SESSION['usuario_id']) {
            $_SESSION['usuario_nome'] = $nome;
        }

        header('Location: ' . $lista . '?msg=editado');
    } else {
        $usuario->cadastrar();
        header('Location: ' . $lista . '?msg=cadastrado');
    }
    exit;
}

if ($acao === 'excluir') {
    $id = (int) ($_GET['id'] ?? 0);

    if ($id === (int) $_SESSION['usuario_id']) {
        header('Location: ' . $lista . '?msg=proprio');
        exit;
    }

    $usuario->id = $id;
    $usuario->excluir();
    header('Location: ' . $lista . '?msg=excluido');
    exit;
}

header('Location: ' . $lista);
