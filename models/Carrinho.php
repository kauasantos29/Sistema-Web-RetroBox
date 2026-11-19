<?php

require_once __DIR__ . '/Jogo.php';

// o carrinho fica na sessão: [id do jogo => quantidade]
class Carrinho
{
    private $jogo;

    public function __construct()
    {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        $this->jogo = new Jogo();
    }

    public function adicionar($id, $qtd = 1)
    {
        $dados = $this->jogo->buscarPorId($id);
        if (!$dados || $qtd < 1) {
            return false;
        }

        $nova = ($_SESSION['carrinho'][$id] ?? 0) + $qtd;
        if ($nova > $dados['estoque']) {
            return false;
        }

        $_SESSION['carrinho'][$id] = $nova;
        return true;
    }

    public function atualizar($id, $qtd)
    {
        $dados = $this->jogo->buscarPorId($id);

        if (!$dados || $qtd < 1) {
            $this->remover($id);
        } else {
            $_SESSION['carrinho'][$id] = min($qtd, $dados['estoque']);
        }
    }

    public function remover($id)
    {
        unset($_SESSION['carrinho'][$id]);
    }

    public function limpar()
    {
        $_SESSION['carrinho'] = [];
    }

    public function itens()
    {
        $itens = [];

        foreach ($_SESSION['carrinho'] as $id => $qtd) {
            $dados = $this->jogo->buscarPorId($id);

            if (!$dados) {
                $this->remover($id);
                continue;
            }

            $dados['quantidade'] = $qtd;
            $dados['subtotal'] = $dados['preco'] * $qtd;
            $itens[] = $dados;
        }

        return $itens;
    }

    public function total()
    {
        $total = 0;
        foreach ($this->itens() as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }

    public function quantidade()
    {
        return array_sum($_SESSION['carrinho']);
    }
}
