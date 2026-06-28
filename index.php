<?php
session_start();
require_once 'config/conexao.php';
require_once 'config/funcoes.php';

$sql = "SELECT n.id, n.titulo, n.noticia, n.data, n.imagem, u.nome AS autor_nome
        FROM noticias n
        JOIN usuarios u ON n.autor = u.id
        ORDER BY n.data DESC";
$result = $conn->query($sql);

$sql_likes = "SELECT n.id, n.titulo, n.imagem, COUNT(l.id) AS total_likes
              FROM noticias n
              LEFT JOIN likes_noticia l ON n.id = l.noticia_id
              GROUP BY n.id
              ORDER BY total_likes DESC
              LIMIT 5";

$result_likes = $conn->query($sql_likes);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/styles/style.css">
</head>

<body>

    <header>
        <div class="header-inner">
            <div class="logo">&lt;<span>portal</span>_tech/&gt;</div>
            <nav>
                <a href="index.php">Início</a>
            </nav>  
            <div class="header-actions">
                <?php if (usuarioLogado()): ?>
                    <a href="dashboard/dashboard.php" class="btn btn-ghost btn-sm">Painel</a>
                    <a href="dashboard/nova_noticia.php" class="btn btn-primary btn-sm">+ Publicar</a>  
                <?php else: ?>
                    <a href="login.php" class="btn btn-ghost btn-sm">Entrar</a>
                    <a href="cadastro.php" class="btn btn-primary btn-sm">Cadastrar</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <section class="banner">
        <div class="banner-label">// últimas atualizações</div>
        <h1>Tecnologia &amp; <em>Inovação</em></h1>
        <p>As notícias mais relevantes sobre tech, IA, startups e o futuro digital.</p>
    </section>

    <div class="container-news-layout">

        <!-- ESQUERDA: notícias -->
        <div class="noticias-main">
            <div class="section-title">Notícias recentes</div>

            <?php if ($result && $result->num_rows > 0): ?>
                <div class="grid-news">
                    <?php while ($n = $result->fetch_assoc()): ?>
                        <article class="card">
                            <div class="card-img">
                                <?php if ($n['imagem']): ?>
                                    <img src="<?= htmlspecialchars($n['imagem']) ?>">
                                <?php endif; ?>
                            </div>

                            <div class="card-body">
                                <h2 class="card-title">
                                    <a href="noticia.php?id=<?= $n['id'] ?>">
                                        <?= htmlspecialchars($n['titulo']) ?>
                                    </a>
                                </h2>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

            <?php else: ?>
                <div class="empty">
                    <div class="empty-icon">📡</div>
                    <h3>Nenhuma notícia publicada ainda</h3>
                    <p>
                        Seja o primeiro a publicar.
                        <a
                            href="<?= function_exists('usuarioLogado') && usuarioLogado() ? 'nova_noticia.php' : 'cadastro.php' ?>">
                            Clique aqui
                        </a>.
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- DIREITA: mais curtidas -->
        <aside class="barra-curtidas">
            <div class="section-title">🔥 Mais curtidas</div>

            <?php if ($result_likes && $result_likes->num_rows > 0): ?>
                <?php while ($l = $result_likes->fetch_assoc()): ?>
                    <div class="like-item">
                        <?php if ($l['imagem']): ?>
                            <img src="<?= htmlspecialchars($l['imagem']) ?>">
                        <?php endif; ?>

                        <div>
                            <a href="noticia.php?id=<?= $l['id'] ?>">
                                <?= htmlspecialchars($l['titulo']) ?>
                            </a>
                            <small>🔥 <?= $l['total_likes'] ?> likes</small>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nenhuma curtida ainda.</p>
            <?php endif; ?>
        </aside>

    </div>




</body>

</html>