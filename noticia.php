<?php
session_start();
require_once 'config/conexao.php';
require_once 'config/funcoes.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

$stmt = $conn->prepare("SELECT n.*, u.nome AS autor_nome, u.perfil AS autor_foto
                        FROM noticias n
                        JOIN usuarios u ON n.autor = u.id
                        WHERE n.id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$n = $stmt->get_result()->fetch_assoc();

if (!$n) { header('Location: index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($n['titulo']) ?> — Portal Tech</title>
    <link rel="stylesheet" href="assets/styles/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <div class="logo"><a href="index.php">&lt;<span>portal</span>_tech/&gt;</a></div>
        <nav>
            <a href="index.php">← Voltar</a>
        </nav>
        <div class="header-actions">
            <?php if (usuarioLogado()): ?>
                <a href="dashboard/dashboard.php" class="btn btn-ghost btn-sm">Painel</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-ghost btn-sm">Entrar</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<div class="article-wrap">
    <div class="article-tag">// tecnologia &amp; inovação</div>
    <h1 class="article-title"><?= htmlspecialchars($n['titulo']) ?></h1>

    <div class="article-meta">
        <span>Por <strong><?= htmlspecialchars($n['autor_nome']) ?></strong></span>
        <span><?= formataData($n['data']) ?></span>
    </div>

    <?php if ($n['imagem']): ?>
        <img src="<?= htmlspecialchars($n['imagem']) ?>" alt="Capa" class="article-img">
    <?php endif; ?>

    <div class="article-body">
        <?php foreach (explode("\n", $n['noticia']) as $p): ?>
            <?php if (trim($p)): ?>
                <p><?= htmlspecialchars($p) ?></p>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="article-actions">
        <a href="index.php" class="btn btn-ghost">← Todas as notícias</a>
        <?php if (usuarioLogado() && $_SESSION['usuario_id'] == $n['autor']): ?>
            <a href="dashboard/editar_noticia.php?id=<?= $n['id'] ?>" class="btn btn-ghost">Editar</a>
            <a href="dashboard/excluir_noticia.php?id=<?= $n['id'] ?>"
               class="btn btn-danger"
               onclick="return confirm('Excluir esta notícia?')">Excluir</a>
        <?php endif; ?>
    </div>
</div>

<footer>
    &lt;<span>portal_tech</span>/&gt; &mdash; <?= date('Y') ?>
</footer>

</body>
</html>