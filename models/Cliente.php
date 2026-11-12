<?php

class Cliente
{
    public $id;
    public $nome;
    public $email;
    public $telefone;
    public $senha;

    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::getConexao();
    }

    public function listar()
    {
        $sql = $this->pdo->query('SELECT id, nome, email, telefone, created_at FROM clientes ORDER BY nome');
        return $sql->fetchAll();
    }

    public function buscarPorId($id)
    {
        $sql = $this->pdo->prepare('SELECT id, nome, email, telefone FROM clientes WHERE id = :id');
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch();
    }

    public function buscarPorNome($nome)
    {
        $sql = $this->pdo->prepare('SELECT id, nome, email, telefone, created_at FROM clientes WHERE nome LIKE :nome ORDER BY nome');
        $sql->bindValue(':nome', '%' . $nome . '%');
        $sql->execute();
        return $sql->fetchAll();
    }

    public function cadastrar()
    {
        $sql = $this->pdo->prepare('INSERT INTO clientes (nome, email, telefone, senha) VALUES (:nome, :email, :telefone, :senha)');
        $sql->bindValue(':nome', $this->nome);
        $sql->bindValue(':email', $this->email);
        $sql->bindValue(':telefone', $this->telefone);
        $sql->bindValue(':senha', password_hash($this->senha, PASSWORD_DEFAULT));
        return $sql->execute();
    }

    public function editar()
    {
        if ($this->senha) {
            $sql = $this->pdo->prepare('UPDATE clientes SET nome = :nome, email = :email, telefone = :telefone, senha = :senha WHERE id = :id');
            $sql->bindValue(':senha', password_hash($this->senha, PASSWORD_DEFAULT));
        } else {
            $sql = $this->pdo->prepare('UPDATE clientes SET nome = :nome, email = :email, telefone = :telefone WHERE id = :id');
        }

        $sql->bindValue(':nome', $this->nome);
        $sql->bindValue(':email', $this->email);
        $sql->bindValue(':telefone', $this->telefone);
        $sql->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $sql->execute();
    }

    public function excluir()
    {
        $sql = $this->pdo->prepare('DELETE FROM clientes WHERE id = :id');
        $sql->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $sql->execute();
    }

    public function emailExiste($email, $ignorarId = 0)
    {
        $sql = $this->pdo->prepare('SELECT COUNT(*) FROM clientes WHERE email = :email AND id <> :id');
        $sql->bindValue(':email', $email);
        $sql->bindValue(':id', $ignorarId, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchColumn() > 0;
    }

    public function possuiPedidos($id)
    {
        $sql = $this->pdo->prepare('SELECT COUNT(*) FROM pedidos WHERE cliente_id = :id');
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchColumn() > 0;
    }

    public function autenticar($email, $senha)
    {
        $sql = $this->pdo->prepare('SELECT * FROM clientes WHERE email = :email');
        $sql->bindValue(':email', $email);
        $sql->execute();
        $cliente = $sql->fetch();

        if ($cliente && password_verify($senha, $cliente['senha'])) {
            return $cliente;
        }

        return false;
    }
}
