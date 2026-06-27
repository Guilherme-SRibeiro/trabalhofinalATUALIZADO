<?php
session_start();
require_once 'config/conexao.php';
require_once 'config/funcoes.php';

if (usuarioLogado()) { header('Location: dashboard.php'); exit; }

$erro = '';
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
            header('Location: dashboard.php');
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <a href="index.php" class="logo">&lt;<span>portal</span>_tech/&gt;</a>
        <div class="header-actions">
            <a href="index.php" class="btn btn-ghost btn-sm">← Início</a>
        </div>
    </div>
</header>

<div class="form-wrap">
    <div class="form-card">
        <h2>Entrar</h2>
        <p class="form-sub">Acesse o painel para publicar notícias.</p>

        <?php if ($aviso === 'acesso_restrito'): ?>
            <div class="alert alert-info">Você precisa estar logado para acessar essa área.</div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required placeholder="seu@email.com">
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="••••••••">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" style="width:100%;">Entrar</button>
            </div>
        </form>

        <p class="form-footer">Não tem conta? <a href="cadastro.php">Criar conta</a></p>
    </div>
</div>

<footer>
    &lt;<span>portal_tech</span>/&gt; &mdash; <?= date('Y') ?>
</footer>

</body>
</html>
