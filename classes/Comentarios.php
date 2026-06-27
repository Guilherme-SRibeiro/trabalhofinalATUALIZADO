<?php

class Comentario
{
    private $conn;
    private $table_name = "comentarios";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Adicionar comentário
    public function registrar($noticia_id, $usuario_id, $comentario)
    {
        $query = "INSERT INTO " . $this->table_name . "
                  (noticia_id, usuario_id, comentario)
                  VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $noticia_id,
            $usuario_id,
            $comentario
        ]);

        return $stmt;
    }

    // Listar comentários de uma notícia
    public function lerPorNoticia($noticia_id)
    {
        $query = "SELECT
                    c.*,
                    u.nome,
                    u.perfil
                  FROM comentarios c
                  INNER JOIN usuarios u
                    ON c.usuario_id = u.id
                  WHERE c.noticia_id = ?
                  ORDER BY c.data_comentario DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$noticia_id]);

        return $stmt;
    }

    // Quantidade de comentários
    public function contar($noticia_id)
    {
        $query = "SELECT COUNT(*) as total
                  FROM comentarios
                  WHERE noticia_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$noticia_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Excluir comentário
    public function deletar($id)
    {
        $query = "DELETE FROM comentarios
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        return $stmt;
    }
}
?>