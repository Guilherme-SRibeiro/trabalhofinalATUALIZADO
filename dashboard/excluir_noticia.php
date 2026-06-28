<?php
session_start();
require_once '../config/conexao.php';
require_once '../config/funcoes.php';

if (!usuarioLogado()) {
    header('Location: ../login.php?aviso=acesso_restrito');
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($id) {
    // Só exclui se a notícia pertencer ao usuário logado
    $stmt = $conn->prepare("DELETE FROM noticias WHERE id = ? AND autor = ?");
    $stmt->bind_param('ii', $id, $_SESSION['usuario_id']);
    $stmt->execute();
}

header('Location: dashboard.php?excluido=1');
exit;