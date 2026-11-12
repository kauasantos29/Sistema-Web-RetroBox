<?php

class Jogo
{
    public const CONSERVACOES = ['Lacrado', 'Completo', 'Só o cartucho'];

    public $id;
    public $titulo;
    public $descricao;
    public $preco;
    public $estoque;
    public $ano_lancamento;
    public $conservacao;
    public $imagem;
    public $plataforma_id;

    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::getConexao();
    }

    public function listar()
    {
        $sql = $this->pdo->query(
            'SELECT j.*, p.nome AS plataforma
             FROM jogos j
             JOIN plataformas p ON p.id = j.plataforma_id
             ORDER BY j.titulo'
        );
        return $sql->fetchAll();
    }

    public function buscarPorId($id)
    {
        $sql = $this->pdo->prepare(
            'SELECT j.*, p.nome AS plataforma
             FROM jogos j
             JOIN plataformas p ON p.id = j.plataforma_id
             WHERE j.id = :id'
        );
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch();
    }

    public function buscarPorNome($nome)
    {
        $sql = $this->pdo->prepare(
            'SELECT j.*, p.nome AS plataforma
             FROM jogos j
             JOIN plataformas p ON p.id = j.plataforma_id
             WHERE j.titulo LIKE :nome
             ORDER BY j.titulo'
        );
        $sql->bindValue(':nome', '%' . $nome . '%');
        $sql->execute();
        return $sql->fetchAll();
    }

    public function filtrarPorPlataforma($plataformaId)
    {
        $sql = $this->pdo->prepare(
            'SELECT j.*, p.nome AS plataforma
             FROM jogos j
             JOIN plataformas p ON p.id = j.plataforma_id
             WHERE j.plataforma_id = :id
             ORDER BY j.titulo'
        );
        $sql->bindValue(':id', $plataformaId, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll();
    }

    public function cadastrar()
    {
        $sql = $this->pdo->prepare(
            'INSERT INTO jogos (titulo, descricao, preco, estoque, ano_lancamento, conservacao, imagem, plataforma_id)
             VALUES (:titulo, :descricao, :preco, :estoque, :ano, :conservacao, :imagem, :plataforma_id)'
        );
        $this->preencher($sql);
        return $sql->execute();
    }

    public function editar()
    {
        $sql = $this->pdo->prepare(
            'UPDATE jogos SET titulo = :titulo, descricao = :descricao, preco = :preco, estoque = :estoque,
             ano_lancamento = :ano, conservacao = :conservacao, imagem = :imagem, plataforma_id = :plataforma_id
             WHERE id = :id'
        );
        $this->preencher($sql);
        $sql->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $sql->execute();
    }

    public function excluir()
    {
        $sql = $this->pdo->prepare('DELETE FROM jogos WHERE id = :id');
        $sql->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $sql->execute();
    }

    public function foiVendido($id)
    {
        $sql = $this->pdo->prepare('SELECT COUNT(*) FROM itens_pedido WHERE jogo_id = :id');
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchColumn() > 0;
    }

    private function preencher($sql)
    {
        $sql->bindValue(':titulo', $this->titulo);
        $sql->bindValue(':descricao', $this->descricao);
        $sql->bindValue(':preco', $this->preco);
        $sql->bindValue(':estoque', $this->estoque, PDO::PARAM_INT);
        $sql->bindValue(':ano', $this->ano_lancamento, $this->ano_lancamento ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $sql->bindValue(':conservacao', $this->conservacao);
        $sql->bindValue(':imagem', $this->imagem);
        $sql->bindValue(':plataforma_id', $this->plataforma_id, PDO::PARAM_INT);
    }
}
