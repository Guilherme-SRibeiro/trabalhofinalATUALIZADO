<?php
session_start();
require_once 'config/conexao.php';
require_once 'config/funcoes.php';
require_once 'classes/Database.php';
require_once 'classes/Usuario.php';

$db  = new Database();
$pdo = $db->getConnection();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome   = trim($_POST['nome']   ?? '');
    $sexo   = trim($_POST['sexo']   ?? '');
    $email  = trim($_POST['email']  ?? '');
    $senha  = $_POST['senha']       ?? '';
    $conf   = $_POST['confirmar']   ?? '';
    $perfil = trim($_POST['perfil'] ?? '');

    if (!$nome || !$email || !$senha) {
        $erro = 'Preencha todos os campos obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter ao menos 6 caracteres.';
    } elseif ($senha !== $conf) {
        $erro = 'As senhas não coincidem.';
    } else {
        $check = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->execute([$email]);
        if ($check->rowCount() > 0) {
            $erro = 'Este e-mail já está cadastrado.';
        } else {
            $usuario = new Usuario($pdo);
            $usuario->registrar($nome, $sexo, $email, $senha);

            $upd = $pdo->prepare("UPDATE usuarios SET perfil = ? WHERE email = ?");
            $upd->execute([$perfil, $email]);

            $novo = $pdo->prepare("SELECT id, nome FROM usuarios WHERE email = ?");
            $novo->execute([$email]);
            $u = $novo->fetch(PDO::FETCH_ASSOC);

            $_SESSION['usuario_id']   = $u['id'];
            $_SESSION['usuario_nome'] = $u['nome'];
            header('Location: dashboard/dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar conta — Portal Tech</title>
    <link rel="stylesheet" href="assets/styles/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <div class="logo"><a href="index.php">&lt;<span>portal</span>_tech/&gt;</a></div>
        <div class="header-actions">
            <a href="login.php" class="btn btn-ghost btn-sm">Entrar</a>
        </div>
    </div>
</header>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-eyebrow">// novo usuário</div>
            <h1>Criar conta</h1>
            <p>Cadastre-se para publicar notícias de tecnologia.</p>
        </div>

        <?php if ($erro): ?>
            <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="nome">Nome completo</label>
                <input type="text" id="nome" name="nome" required placeholder="Seu nome"
                       value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="sexo">Sexo</label>
                <div class="select-wrap">
                    <select id="sexo" name="sexo">
                        <option value="">Prefiro não informar</option>
                        <option value="masculino"         <?= ($_POST['sexo'] ?? '') === 'masculino'         ? 'selected' : '' ?>>Masculino</option>
                        <option value="feminino"          <?= ($_POST['sexo'] ?? '') === 'feminino'          ? 'selected' : '' ?>>Feminino</option>
                        <option value="outro"             <?= ($_POST['sexo'] ?? '') === 'outro'             ? 'selected' : '' ?>>Outro</option>
                        <option value="prefiro_nao_dizer" <?= ($_POST['sexo'] ?? '') === 'prefiro_nao_dizer' ? 'selected' : '' ?>>Prefiro não dizer</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="perfil">Foto de perfil <span style="font-weight:400;font-size:.78rem;">(opcional)</span></label>
                <input type="url" id="perfil" name="perfil" placeholder="https://exemplo.com/sua-foto.jpg"
                       value="<?= htmlspecialchars($_POST['perfil'] ?? '') ?>">
                <small class="form-hint">Cole o link de uma imagem da internet</small>
            </div>

            <div id="preview-wrap" style="display:none;margin-top:-8px;">
                <img id="preview-img" src="" alt="Preview"
                     style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:2px solid var(--accent);">
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required placeholder="seu@email.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required placeholder="Mínimo 6 caracteres">
            </div>

            <div class="form-group">
                <label for="confirmar">Confirmar senha</label>
                <input type="password" id="confirmar" name="confirmar" required placeholder="Repita a senha">
            </div>

            <button type="submit" class="btn btn-primary btn-full">Criar conta</button>
        </form>

        <p class="auth-footer">Já tem conta? <a href="login.php">Entrar</a></p>
    </div>
</div>

<footer>
    &lt;<span>portal_tech</span>/&gt; &mdash; <?= date('Y') ?>
</footer>

<script>
const perfilInput = document.getElementById('perfil');
const previewWrap = document.getElementById('preview-wrap');
const previewImg  = document.getElementById('preview-img');

perfilInput.addEventListener('input', () => {
    const url = perfilInput.value.trim();
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