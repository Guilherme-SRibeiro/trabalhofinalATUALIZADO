<?php
session_start();
require_once '../config/conexao.php';
require_once '../config/funcoes.php';

if (!usuarioLogado()) {
    header('Location: ../login.php?aviso=acesso_restrito');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Dados do usuário
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param('i', $usuario_id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

$sql = "SELECT n.id, n.titulo, n.imagem, n.data,
               COUNT(DISTINCT c.id) AS total_comentarios,
               COUNT(DISTINCT l.id) AS total_likes
        FROM noticias n
        LEFT JOIN comentarios c ON c.noticia_id = n.id
        LEFT JOIN likes_noticia l ON l.noticia_id = n.id
        WHERE n.autor = ?
        GROUP BY n.id
        ORDER BY n.data DESC";
$stmt2 = $conn->prepare($sql);
$stmt2->bind_param('i', $usuario_id);
$stmt2->execute();
$noticias = $stmt2->get_result();

$stmtStats = $conn->prepare(
    "SELECT
        COUNT(DISTINCT n.id)  AS total_noticias,
        COUNT(DISTINCT l.id)  AS total_likes,
        COUNT(DISTINCT c.id)  AS total_comentarios
     FROM noticias n
     LEFT JOIN likes_noticia l ON l.noticia_id = n.id
     LEFT JOIN comentarios c   ON c.noticia_id = n.id
     WHERE n.autor = ?"
);
$stmtStats->bind_param('i', $usuario_id);
$stmtStats->execute();
$stats = $stmtStats->get_result()->fetch_assoc();

$publicado = isset($_GET['publicado']);
$excluido  = isset($_GET['excluido']);
$editado   = isset($_GET['editado']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel — portal_tech</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <div class="logo"><a href="../index.php">&lt;<span>portal</span>_tech/&gt;</a></div>
        <nav>
            <a href="../index.php">Início</a>
            <a href="dashboard.php" class="active">Painel</a>
        </nav>
        <div class="header-actions">
            <a href="nova_noticia.php" class="btn btn-primary btn-sm">+ Publicar</a>
            <a href="../logout.php" class="btn btn-ghost btn-sm">Sair</a>
        </div>
    </div>
</header>

<div class="dash-wrapper">

    <?php if ($publicado): ?>
        <div class="alert alert-sucesso">✅ Notícia publicada com sucesso!</div>
    <?php endif; ?>
    <?php if ($excluido): ?>
        <div class="alert alert-info">🗑️ Notícia excluída.</div>
    <?php endif; ?>
    <?php if ($editado): ?>
        <div class="alert alert-sucesso">✅ Notícia atualizada com sucesso!</div>
    <?php endif; ?>

    <div class="dash-profile">
        <div class="dash-avatar">
            <?php if (!empty($usuario['perfil'])): ?>
                <img src="<?= htmlspecialchars($usuario['perfil']) ?>" alt="Foto de perfil">
            <?php else: ?>
                <div class="avatar-placeholder">
                    <?= strtoupper(mb_substr($usuario['nome'], 0, 1)) ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="dash-profile-info">
            <div class="auth-eyebrow">// painel do autor</div>
            <h1><?= htmlspecialchars($usuario['nome']) ?></h1>
            <p class="dash-email"><?= htmlspecialchars($usuario['email']) ?></p>
        </div>
    </div>

    <div class="dash-stats">
        <div class="stat-card">
            <div class="stat-value"><?= $stats['total_noticias'] ?></div>
            <div class="stat-label">Publicações</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['total_likes'] ?></div>
            <div class="stat-label">Curtidas recebidas</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['total_comentarios'] ?></div>
            <div class="stat-label">Comentários</div>
        </div>
    </div>

    <div class="dash-actions-bar">
        <div class="section-title">Minhas publicações</div>
        <a href="nova_noticia.php" class="btn btn-primary btn-sm">+ Nova notícia</a>
    </div>

    <?php if ($noticias->num_rows > 0): ?>
        <div class="dash-news-list">
            <?php while ($n = $noticias->fetch_assoc()): ?>
                <div class="dash-news-item">
                    <div class="dash-news-thumb">
                        <?php if ($n['imagem']): ?>
                            <img src="<?= htmlspecialchars($n['imagem']) ?>" alt="">
                        <?php else: ?>
                            <div class="thumb-placeholder">📰</div>
                        <?php endif; ?>
                    </div>

                    <div class="dash-news-meta">
                        <a href="../noticia.php?id=<?= $n['id'] ?>" class="dash-news-title" target="_blank">
                            <?= htmlspecialchars($n['titulo']) ?>
                        </a>
                        <div class="dash-news-info">
                            <span>📅 <?= formataData($n['data']) ?></span>
                            <span>🔥 <?= $n['total_likes'] ?> likes</span>
                            <span>💬 <?= $n['total_comentarios'] ?> comentários</span>
                        </div>
                    </div>

                    <div class="dash-news-btns">
                        <a href="editar_noticia.php?id=<?= $n['id'] ?>" class="btn btn-ghost btn-sm">Editar</a>
                        <a href="excluir_noticia.php?id=<?= $n['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Tem certeza que deseja excluir esta notícia?')">Excluir</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty">
            <div class="empty-icon">✍️</div>
            <h3>Nenhuma publicação ainda</h3>
            <p>Comece agora. <a href="nova_noticia.php">Escreva sua primeira notícia</a>.</p>
        </div>
    <?php endif; ?>

</div>
</body>
</html>