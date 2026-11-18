<?php

require 'autenticacao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $editora = $_POST['editora'];
    $isbn = preg_replace('/\D/', '', $_POST['isbn']);
    $genero = $_POST['genero'];
    $ano = (int) $_POST['ano'];
    $quantidade = (int) $_POST['quantidade'];

    if ($isbn !== '' && strlen($isbn) !== 10 && strlen($isbn) !== 13) {
        $erro = 'O ISBN deve ter 10 ou 13 números.';
    } else {
        if ($isbn === '') {
            $isbn = null;
        }

        $stm = $conexao->prepare('INSERT INTO livros (titulo, autor, editora, isbn, genero, ano_publicacao, quantidade_exemplares) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stm->bind_param('sssssii', $titulo, $autor, $editora, $isbn, $genero, $ano, $quantidade);

        if ($stm->execute()) {
            header('Location: livros.php');
            exit;
        }

        $erro = 'Erro ao cadastrar. Confira se o ISBN já existe.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar livro</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class="caixa pequena">
        <h1>Cadastrar livro</h1>
        
        <?php if (!empty($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Título</label>
            <input
                name="titulo"
                required
            >

            <label>Autor</label>
            <input
                name="autor"
                required
            >

            <label>Editora</label>
            <input
                name="editora"
            >

            <label>ISBN</label>
            <input
                name="isbn"
                maxlength="13"
                pattern="[0-9]{10}|[0-9]{13}"
                title="Digite 10 ou 13 números"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            >

            <label>Gênero</label>
            <input
                name="genero"
                required
            >

            <label>Ano de publicação</label>
            <input
                type="number"
                name="ano"
            >

            <label>Quantidade de exemplares</label>
            <input
                type="number"
                name="quantidade"
                min="1"
                value="1"
                required
            >

            <div class="botoes">
                <button type="submit">Cadastrar</button>
                <a href="livros.php">Cancelar</a>
            </div>
        </form>
    </main>
</body>

</html>