<?php

require_once __DIR__ . '/Jogo.php';

class Pedido
{
    public const STATUS = ['Pendente', 'Pago', 'Enviado', 'Entregue', 'Cancelado'];

    public $id;
    public $cliente_id;
    public $data_pedido;
    public $status;
    public $total;

    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::getConexao();
    }

    public function listar($status = '')
    {
        $sql = 'SELECT p.*, c.nome AS cliente
                FROM pedidos p
                JOIN clientes c ON c.id = p.cliente_id';

        if ($status !== '') {
            $sql .= ' WHERE p.status = :status';
        }

        $sql = $this->pdo->prepare($sql . ' ORDER BY p.data_pedido DESC, p.id DESC');

        if ($status !== '') {
            $sql->bindValue(':status', $status);
        }

        $sql->execute();
        return $sql->fetchAll();
    }

    public function buscarPorId($id)
    {
        $sql = $this->pdo->prepare(
            'SELECT p.*, c.nome AS cliente, c.email, c.telefone
             FROM pedidos p
             JOIN clientes c ON c.id = p.cliente_id
             WHERE p.id = :id'
        );
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch();
    }

    public function listarPorCliente($clienteId)
    {
        $sql = $this->pdo->prepare('SELECT * FROM pedidos WHERE cliente_id = :id ORDER BY data_pedido DESC, id DESC');
        $sql->bindValue(':id', $clienteId, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll();
    }

    public function listarItens($pedidoId)
    {
        $sql = $this->pdo->prepare(
            'SELECT i.*, j.titulo, (i.quantidade * i.preco_unitario) AS subtotal
             FROM itens_pedido i
             JOIN jogos j ON j.id = i.jogo_id
             WHERE i.pedido_id = :id'
        );
        $sql->bindValue(':id', $pedidoId, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll();
    }

    public function cadastrar()
    {
        $sql = $this->pdo->prepare('INSERT INTO pedidos (cliente_id, status, total) VALUES (:cliente_id, :status, :total)');
        $sql->bindValue(':cliente_id', $this->cliente_id, PDO::PARAM_INT);
        $sql->bindValue(':status', $this->status ?: 'Pendente');
        $sql->bindValue(':total', $this->total);
        $sql->execute();

        $this->id = $this->pdo->lastInsertId();
        return $this->id;
    }

    public function adicionarItem($jogoId, $qtd, $preco)
    {
        $sql = $this->pdo->prepare(
            'INSERT INTO itens_pedido (pedido_id, jogo_id, quantidade, preco_unitario)
             VALUES (:pedido_id, :jogo_id, :quantidade, :preco)'
        );
        $sql->bindValue(':pedido_id', $this->id, PDO::PARAM_INT);
        $sql->bindValue(':jogo_id', $jogoId, PDO::PARAM_INT);
        $sql->bindValue(':quantidade', $qtd, PDO::PARAM_INT);
        $sql->bindValue(':preco', $preco);
        return $sql->execute();
    }

    public function editar()
    {
        $sql = $this->pdo->prepare('UPDATE pedidos SET status = :status WHERE id = :id');
        $sql->bindValue(':status', $this->status);
        $sql->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $sql->execute();
    }

    public function excluir()
    {
        $sql = $this->pdo->prepare('DELETE FROM pedidos WHERE id = :id');
        $sql->bindValue(':id', $this->id, PDO::PARAM_INT);
        return $sql->execute();
    }

    // salva o pedido, os itens e baixa o estoque; se algo falhar, nada é salvo
    public function finalizar($clienteId, $itens)
    {
        $total = 0;
        foreach ($itens as $item) {
            $total += $item['subtotal'];
        }

        try {
            $this->pdo->beginTransaction();

            $this->cliente_id = $clienteId;
            $this->status = 'Pendente';
            $this->total = $total;
            $this->cadastrar();

            $jogo = new Jogo();
            foreach ($itens as $item) {
                $jogo->id = $item['id'];

                if (!$jogo->baixarEstoque($item['quantidade'])) {
                    $this->pdo->rollBack();
                    return false;
                }

                $this->adicionarItem($item['id'], $item['quantidade'], $item['preco']);
            }

            $this->pdo->commit();
            return $this->id;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function devolverEstoque($pedidoId)
    {
        $jogo = new Jogo();
        foreach ($this->listarItens($pedidoId) as $item) {
            $jogo->id = $item['jogo_id'];
            $jogo->devolverEstoque($item['quantidade']);
        }
    }

    public function contar()
    {
        return $this->pdo->query('SELECT COUNT(*) FROM pedidos')->fetchColumn();
    }

    public function totalVendido()
    {
        return $this->pdo->query("SELECT COALESCE(SUM(total), 0) FROM pedidos WHERE status <> 'Cancelado'")->fetchColumn();
    }

    public function ultimos($quantidade = 5)
    {
        $sql = $this->pdo->prepare(
            'SELECT p.*, c.nome AS cliente
             FROM pedidos p
             JOIN clientes c ON c.id = p.cliente_id
             ORDER BY p.data_pedido DESC, p.id DESC
             LIMIT :qtd'
        );
        $sql->bindValue(':qtd', $quantidade, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetchAll();
    }
}
