<?php

namespace Model;

use PDO;

class Lancamento
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Connection::getInstance();
    }

    public function salvar(float $valor, string $tipo, string $categoria, string $data): bool
    {
        $sql = 'INSERT INTO lancamentos (valor, tipo, categoria, data) VALUES (?, ?, ?, ?)';
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$valor, $tipo, $categoria, $data]);
    }

    public function listar(string $mesAno, ?string $categoria = null): array
    {
        $sql = "SELECT * FROM lancamentos WHERE DATE_FORMAT(data, '%Y-%m') = ?";
        $params = [$mesAno];

        if ($categoria !== null && $categoria !== '') {
            $sql .= ' AND categoria = ?';
            $params[] = $categoria;
        }

        $sql .= ' ORDER BY data DESC, id DESC';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = 'SELECT * FROM lancamentos WHERE id = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }

    public function atualizar(int $id, float $valor, string $tipo, string $categoria, string $data): bool
    {
        $sql = 'UPDATE lancamentos SET valor = ?, tipo = ?, categoria = ?, data = ? WHERE id = ?';
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$valor, $tipo, $categoria, $data, $id]);
    }

    public function excluir(int $id): bool
    {
        $sql = 'DELETE FROM lancamentos WHERE id = ?';
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function totaisPorCategoria(string $mesAno): array
    {
        $sql = "SELECT categoria, tipo, SUM(valor) AS total FROM lancamentos WHERE DATE_FORMAT(data, '%Y-%m') = ? GROUP BY categoria, tipo ORDER BY categoria";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$mesAno]);
        return $stmt->fetchAll();
    }
}
