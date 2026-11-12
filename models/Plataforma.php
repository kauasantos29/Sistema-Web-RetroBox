<?php

class Plataforma
{
    public $id;
    public $nome;
    public $fabricante;

    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::getConexao();
    }

    public function listar()
    {
        $sql = $this->pdo->query('SELECT * FROM plataformas ORDER BY nome');
        return $sql->fetchAll();
    }

    public function buscarPorId($id)
    {
        $sql = $this->pdo->prepare('SELECT * FROM plataformas WHERE id = :id');
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch();
    }

    public function cadastrar()
    {
        $sql = $this->pdo->prepare('INSERT INTO plataformas (nome, fabricante) VALUES (:nome, :fabricante)');
        $sql->bindValue(':nome', $this->nome);
        $sql->bindValue(':fabricante', $this->fabricante);
        return $sql->execute();
    }

    public function editar()
    {
        $sql = $this->pdo->prepare('UPDATE plataformas SET nome = :nome, fabricante = :fabricante WHERE id = :id');
        $sql->bindValue(':nome', $this->nome);
        $sql->bindValue(':fabricante', $this->fabricante);
        $sql->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $sql->execute();
    }

    public function excluir()
    {
        $sql = $this->pdo->prepare('DELETE FROM plataformas WHERE id = :id');
        $sql->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $sql->execute();
    }

    public function nomeExiste($nome, $ignorarId = 0)
    {
        $sql = $this->pdo->prepare('SELECT COUNT(*) FROM plataformas WHERE nome = :nome AND id <> :id');
        $sql->bindValue(':nome', $nome);
        $sql->bindValue(':id', $ignorarId, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchColumn() > 0;
    }

    public function possuiJogos($id)
    {
        $sql = $this->pdo->prepare('SELECT COUNT(*) FROM jogos WHERE plataforma_id = :id');
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchColumn() > 0;
    }
}
