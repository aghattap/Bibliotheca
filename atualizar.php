<?php

require 'autenticacao.php';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$stm = $conexao->prepare('SELECT * FROM clientes WHERE id = ?');
$stm->bind_param('i', $id);
$stm->execute();
$leitor = $stm->get_result()->fetch_assoc();

if (!$leitor) {
    die('Leitor não encontrado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = preg_replace('/\D/', '', $_POST['telefone']);

    if (strlen($telefone) < 10 || strlen($telefone) > 11) {
        $erro = 'O telefone deve ter 10 ou 11 números.';
    } else {
        $sql = 'UPDATE clientes SET nome = ?, email = ?, telefone = ? WHERE id = ?';

        $stm = $conexao->prepare($sql);
        $stm->bind_param('sssi', $nome, $email, $telefone, $id);
        if ($stm->execute()) {
            header('Location: clientes.php');
            exit;
        }   

        $erro = 'Erro ao atualizar o leitor.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar leitor</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class="caixa pequena">
        <h1>Atualizar leitor</h1>

        <?php if (!empty($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input
                type="hidden"
                name="id"
                value="<?php echo $id; ?>"
            >

            <label>Nome</label>
            <input
                type="text"
                name="nome"
                value="<?php echo htmlspecialchars($leitor['nome']); ?>"
                required
            >

            <label>E-mail</label>
            <input
                type="email"
                name="email"
                value="<?php echo htmlspecialchars($leitor['email']); ?>"
                required
            >

            <label>Telefone</label>
            <input
                type="text"
                name="telefone"
                maxlength="11"
                pattern="[0-9]{10,11}"
                title="Digite 10 ou 11 números"
                value="<?php echo htmlspecialchars($leitor['telefone']); ?>"
                required
            >

            <div class="botoes">
                <button type="submit">Atualizar</button>
                <a href="clientes.php">Cancelar</a>
            </div>
        </form>
    </main>
</body>

</html>