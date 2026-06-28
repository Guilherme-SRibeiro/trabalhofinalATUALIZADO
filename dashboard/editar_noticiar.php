<?php
session_start();
require_once '../config/conexao.php';
require_once '../config/funcoes.php';

if (!usuarioLogado()) {
    header('Location: ../login.php?aviso=acesso_restrito');
    exit;
}

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM noticias WHERE id = ? AND autor = ?");
$stmt->bind_param('ii', $id, $_SESSION['usuario_id']);
$stmt->execute();
$noticia = $stmt->get_result()->fetch_assoc();

if (!$noticia) {
    header('Location: dashboard.php');
    exit;
}

$erro    = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo   = trim($_POST['titulo']  ?? '');
    $conteudo = trim($_POST['noticia'] ?? '');
    $imagem   = trim($_POST['imagem']  ?? '');

    if (empty($titulo) || empty($conteudo)) {
        $erro = 'Título e conteúdo são obrigatórios.';
    } else {
        $imagem_val = !empty($imagem) ? $imagem : null;
        $upd = $conn->prepare(
            "UPDATE noticias SET titulo = ?, noticia = ?, imagem = ? WHERE id = ? AND autor = ?"
        );
        $upd->bind_param('sssii', $titulo, $conteudo, $imagem_val, $id, $_SESSION['usuario_id']);

        if ($upd->execute()) {
            header('Location: dashboard.php?editado=1');
            exit;
        } else {
            $erro = 'Erro ao salvar. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar notícia — portal_tech</title>
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
        <div class="auth-eyebrow">// editando publicação</div>
        <h1>Editar notícia</h1>
    </div>

    <?php if ($erro): ?>
        <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST" class="news-form">
        <div class="form-group">
            <label for="titulo">Título <span class="required">*</span></label>
            <input type="text" id="titulo" name="titulo"
                   value="<?= htmlspecialchars($noticia['titulo']) ?>"
                   required autofocus>
        </div>

        <div class="form-group">
            <label for="imagem">URL da imagem de capa</label>
            <input type="url" id="imagem" name="imagem"
                   value="<?= htmlspecialchars($noticia['imagem'] ?? '') ?>"
                   placeholder="https://...">
        </div>

        <div class="img-preview-wrap" id="preview-wrap"
             style="<?= empty($noticia['imagem']) ? 'display:none' : '' ?>">
            <img id="preview-img"
                 src="<?= htmlspecialchars($noticia['imagem'] ?? '') ?>"
                 alt="Capa atual">
        </div>

        <div class="form-group">
            <label for="noticia">Conteúdo <span class="required">*</span></label>
            <textarea id="noticia" name="noticia" rows="14" required><?= htmlspecialchars($noticia['noticia']) ?></textarea>
        </div>

        <div class="form-actions">
            <a href="dashboard.php" class="btn btn-ghost">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar alterações</button>
        </div>
    </form>
</div>

<script>
const imgInput    = document.getElementById('imagem');
const previewWrap = document.getElementById('preview-wrap');
const previewImg  = document.getElementById('preview-img');

imgInput.addEventListener('input', () => {
    const url = imgInput.value.trim();
    previewImg.src = url;
    if (url) {
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