<?php

class Usuario
{
    public const TIPOS = ['admin', 'funcionario'];

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

    public function listar()
    {
        $sql = $this->pdo->query('SELECT id, nome, email, tipo, created_at FROM usuarios ORDER BY nome');
        return $sql->fetchAll();
    }

    public function buscarPorId($id)
    {
        $sql = $this->pdo->prepare('SELECT id, nome, email, tipo FROM usuarios WHERE id = :id');
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch();
    }

    public function buscarPorNome($nome)
    {
        $sql = $this->pdo->prepare('SELECT id, nome, email, tipo, created_at FROM usuarios WHERE nome LIKE :nome ORDER BY nome');
        $sql->bindValue(':nome', '%' . $nome . '%');
        $sql->execute();
        return $sql->fetchAll();
    }

    public function cadastrar()
    {
        $sql = $this->pdo->prepare('INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)');
        $sql->bindValue(':nome', $this->nome);
        $sql->bindValue(':email', $this->email);
        $sql->bindValue(':senha', password_hash($this->senha, PASSWORD_DEFAULT));
        $sql->bindValue(':tipo', $this->tipo);
        return $sql->execute();
    }

    public function editar()
    {
        if ($this->senha) {
            $sql = $this->pdo->prepare('UPDATE usuarios SET nome = :nome, email = :email, tipo = :tipo, senha = :senha WHERE id = :id');
            $sql->bindValue(':senha', password_hash($this->senha, PASSWORD_DEFAULT));
        } else {
            $sql = $this->pdo->prepare('UPDATE usuarios SET nome = :nome, email = :email, tipo = :tipo WHERE id = :id');
        }

        $sql->bindValue(':nome', $this->nome);
        $sql->bindValue(':email', $this->email);
        $sql->bindValue(':tipo', $this->tipo);
        $sql->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $sql->execute();
    }

    public function excluir()
    {
        $sql = $this->pdo->prepare('DELETE FROM usuarios WHERE id = :id');
        $sql->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $sql->execute();
    }

    public function emailExiste($email, $ignorarId = 0)
    {
        $sql = $this->pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE email = :email AND id <> :id');
        $sql->bindValue(':email', $email);
        $sql->bindValue(':id', $ignorarId, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchColumn() > 0;
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
