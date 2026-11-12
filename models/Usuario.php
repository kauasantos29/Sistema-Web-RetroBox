<?php

class Usuario
{
    public $id;
    public $nome;
    public $email;
    public $senha;
    public $tipo;

    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::getConexao();
    }

    public function autenticar($email, $senha)
    {
        $sql = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
        $sql->bindValue(':email', $email);
        $sql->execute();
        $usuario = $sql->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }

        return false;
    }
}
