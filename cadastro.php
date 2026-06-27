<?php
include_once 'config/conexao.php';
include_once 'classes/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario($db);
    $nome  = $_POST['nome'];
    $sexo  = $_POST['sexo'];
    $fone  = $_POST['fone'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $usuario->criar($nome, $sexo, $fone, $email, $senha);
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Usuário</title>
</head>
<body>
    <div class="container">
        <h1>Adicionar Usuário</h1>
        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label>Sexo:</label>
            <div class="radio-group">
                <label>
                    <input type="radio" id="masculino" name="sexo" value="M" required> Masculino
                </label>
                <label>
                    <input type="radio" id="feminino" name="sexo" value="F"> Feminino
                </label>
            </div>

            <label for="fone">Fone:</label>
            <input type="text" id="fone" name="fone" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <input type="submit" value="Adicionar">
        </form>
        <div class="voltar">
            <a href="index.php">← Voltar ao login</a>
        </div>
    </div>
</body>
</html>