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
