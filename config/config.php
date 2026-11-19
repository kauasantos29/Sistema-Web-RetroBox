<?php

define('BASE_URL', '/retrobox/');

session_start();

require_once __DIR__ . '/conexao.php';

function usuarioLogado()
{
    return isset($_SESSION['usuario_id']);
}

function exigirLogin()
{
    if (!usuarioLogado()) {
        header('Location: ' . BASE_URL . 'views/login.php');
        exit;
    }
}

function clienteLogado()
{
    return isset($_SESSION['cliente_id']);
}

function ehAdmin()
{
    return usuarioLogado() && $_SESSION['usuario_tipo'] === 'admin';
}

function exigirAdmin()
{
    exigirLogin();

    if (!ehAdmin()) {
        header('Location: ' . BASE_URL . 'views/admin/painel.php');
        exit;
    }
}
