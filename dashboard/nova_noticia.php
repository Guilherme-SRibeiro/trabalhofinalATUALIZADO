<?php
session_start();
require_once '../config/conexao.php';
require_once '../config/funcoes.php';

if (!usuarioLogado()) {
    header('Location: ../login.php?aviso=acesso_restrito');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo   = trim($_POST['titulo']   ?? '');
    $conteudo = trim($_POST['noticia']  ?? '');
    $imagem   = trim($_POST['imagem']   ?? '');
    $autor    = $_SESSION['usuario_id'];
    $data     = date('Y-m-d H:i:s');

    if (empty($titulo) || empty($conteudo)) {
        $erro = 'Título e conteúdo são obrigatórios.';
    } else {
        $imagem_val = !empty($imagem) ? $imagem : null;
        $stmt = $conn->prepare(
            "INSERT INTO noticias (titulo, noticia, data, autor, imagem) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('sssss', $titulo, $conteudo, $data, $autor, $imagem_val);

        if ($stmt->execute()) {
            header('Location: dashboard.php?publicado=1');
            exit;
        } else {
            $erro = 'Erro ao publicar. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova notícia — portal_tech</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <div class="logo"><a href="../index.php">&lt;<span>portal</span>_tech/&gt;</a></div>
        <nav>
            <a href="../index.php">Início</a>
            <a href="dashboard.php">Painel</a>
        </nav>
        <div class="header-actions">
            <a href="../logout.php" class="btn btn-ghost btn-sm">Sair</a>
        </div>
    </div>
</header>

<div class="dash-wrapper">
    <div class="form-page-header">
        <div class="auth-eyebrow">// nova publicação</div>
        <h1>Escrever notícia</h1>
    </div>

    <?php if ($erro): ?>
        <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST" class="news-form">
        <div class="form-group">
            <label for="titulo">Título <span class="required">*</span></label>
            <input type="text" id="titulo" name="titulo"
                   value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>"
                   placeholder="Um título chamativo..." required autofocus>
        </div>

        <div class="form-group">
            <label for="imagem">URL da imagem de capa</label>
            <input type="url" id="imagem" name="imagem"
                   value="<?= htmlspecialchars($_POST['imagem'] ?? '') ?>"
                   placeholder="https://...">
            <small class="form-hint">Cole o link de uma imagem para usar como capa.</small>
        </div>

        <div id="preview-wrap" style="display:none" class="img-preview-wrap">
            <img id="preview-img" src="" alt="Pré-visualização da capa">
        </div>

        <div class="form-group">
            <label for="noticia">Conteúdo <span class="required">*</span></label>
            <textarea id="noticia" name="noticia" rows="14"
                      placeholder="Escreva o conteúdo completo da notícia..." required><?= htmlspecialchars($_POST['noticia'] ?? '') ?></textarea>
        </div>

        <div class="form-actions">
            <a href="dashboard.php" class="btn btn-ghost">Cancelar</a>
            <button type="submit" class="btn btn-primary">Publicar notícia</button>
        </div>
    </form>
</div>

<script>
const imgInput   = document.getElementById('imagem');
const previewWrap = document.getElementById('preview-wrap');
const previewImg  = document.getElementById('preview-img');

imgInput.addEventListener('input', () => {
    const url = imgInput.value.trim();
    if (url) {
        previewImg.src = url;
        previewWrap.style.display = 'block';
        previewImg.onerror = () => { previewWrap.style.display = 'none'; };
        previewImg.onload  = () => { previewWrap.style.display = 'block'; };
    } else {
        previewWrap.style.display = 'none';
    }
});
</script>
</body>
</html>