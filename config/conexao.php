<?php

class Conexao
{
    private static $pdo;

    public static function getConexao()
    {
        if (!self::$pdo) {
            try {
                self::$pdo = new PDO('mysql:host=localhost;dbname=retrobox;charset=utf8mb4', 'root', '');
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Erro ao conectar com o banco: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
