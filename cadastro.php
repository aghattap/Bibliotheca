<?php

require 'autenticacao.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = preg_replace('/\D/', '', $_POST['telefone']);

    if (strlen($telefone) < 10 || strlen($telefone) > 11) {
        $mensagem = 'O telefone deve ter 10 ou 11 números.';
    } else {
        $stm = $conexao->prepare('INSERT INTO clientes (nome, email, telefone) VALUES (?, ?, ?)');
        $stm->bind_param('sss', $nome, $email, $telefone);

        if ($stm->execute()) {
            header('Location: clientes.php');
            exit;
        }

        $mensagem = 'Erro ao cadastrar leitor.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar leitor</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class="caixa pequena">
        <h1>Cadastrar leitor</h1>
        
        <?php if ($mensagem): ?>
            <p class="erro"><?php echo $mensagem; ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <label>Nome</label>
            <input
                type="text"
                name="nome"
                required
            >

            <label>E-mail</label>
            <input
                type="email"
                name="email"
                required
            >

            <label>Telefone</label>
            <input
                type="text"
                name="telefone"
                maxlength="11"
                pattern="[0-9]{10,11}"
                title="Digite 10 ou 11 números"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                required
            >

            <div class="botoes">
                <button type="submit">Cadastrar</button>
                <a href="clientes.php">Cancelar</a>
            </div>
        </form>
    </main>
</body>

</html>