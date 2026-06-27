<?php

class Like
{
    private $conn;
    private $table_name = "likes_noticia";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Curtir notícia
    public function curtir($noticia_id, $usuario_id)
    {
        // verifica se já curtiu
        if ($this->jaCurtiu($noticia_id, $usuario_id)) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . "
                  (noticia_id, usuario_id)
                  VALUES (?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $noticia_id,
            $usuario_id
        ]);

        return true;
    }

    // Remover curtida
    public function descurtir($noticia_id, $usuario_id)
    {
        $query = "DELETE FROM " . $this->table_name . "
                  WHERE noticia_id = ?
                  AND usuario_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $noticia_id,
            $usuario_id
        ]);

        return $stmt;
    }

    // Verifica se usuário já curtiu
    public function jaCurtiu($noticia_id, $usuario_id)
    {
        $query = "SELECT *
                  FROM " . $this->table_name . "
                  WHERE noticia_id = ?
                  AND usuario_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $noticia_id,
            $usuario_id
        ]);

        return $stmt->rowCount() > 0;
    }

    // Contar likes
    public function contar($noticia_id)
    {
        $query = "SELECT COUNT(*) as total
                  FROM " . $this->table_name . "
                  WHERE noticia_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$noticia_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>