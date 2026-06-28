<?php
session_start();
require_once 'config/conexao.php';
require_once 'config/funcoes.php';

if (usuarioLogado()) { header('Location: dashboard/dashboard.php'); exit; }

$erro  = '';
$aviso = $_GET['aviso'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($conn, $_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email && $senha) {
        $stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $u = $stmt->get_result()->fetch_assoc();

        if ($u && password_verify($senha, $u['senha'])) {
            $_SESSION['usuario_id']   = $u['id'];
            $_SESSION['usuario_nome'] = $u['nome'];
            header('Location: dashboard/dashboard.php');
            exit;
        } else {
            $erro = 'E-mail ou senha incorretos.';
        }
    } else {
        $erro = 'Preencha todos os campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar — Portal Tech</title>
    <link rel="stylesheet" href="assets/styles/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <div class="logo"><a href="index.php">&lt;<span>portal</span>_tech/&gt;</a></div>
        <div class="header-actions">
            <a href="index.php" class="btn btn-ghost btn-sm">← Início</a>
        </div>
    </div>
</header>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-eyebrow">// acesso ao portal</div>
            <h1>Entrar</h1>
            <p>Acesse o painel para publicar notícias.</p>
        </div>

        <?php if ($aviso === 'acesso_restrito'): ?>
            <div class="alert alert-info">Você precisa estar logado para acessar essa área.</div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required placeholder="seu@email.com">
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary btn-full">Entrar</button>
        </form>

        <p class="auth-footer">Não tem conta? <a href="cadastro.php">Criar conta</a></p>
    </div>
</div>

<footer>
    &lt;<span>portal_tech</span>/&gt; &mdash; <?= date('Y') ?>
</footer>

</body>
</html>