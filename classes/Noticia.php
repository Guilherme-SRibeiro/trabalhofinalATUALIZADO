<?php
class Noticia
{
    private $conn;
    private $table_name = "noticias";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function registrar($titulo, $noticia, $data, $autor, $imagem)
    {
        $query = "INSERT INTO " . $this->table_name .
            " (titulo, noticia, data, autor, imagem)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$titulo, $noticia, $data, $autor, $imagem]);

        return $stmt;
    }

    public function ler()
    {
        $query = "
        SELECT n.*, u.nome,
            COUNT(DISTINCT c.id) AS total_comentarios,
            COUNT(DISTINCT l.id) AS total_likes
        FROM noticias n INNER JOIN usuarios u ON n.autor = u.id
        LEFT JOIN comentarios c ON n.id = c.noticia_id
        LEFT JOIN likes_noticia l ON n.id = l.noticia_id
        GROUP BY n.id ORDER BY n.data DESC
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function lerPorId($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $titulo, $noticia, $imagem)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET titulo = ?, noticia = ?, imagem = ?
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$titulo, $noticia, $imagem, $id]);

        return $stmt;
    }

    public function deletar($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        return $stmt;
    }
}
?>