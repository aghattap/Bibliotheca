<?php

require 'autenticacao.php';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$stm = $conexao->prepare('SELECT * FROM livros WHERE id = ?');
$stm->bind_param('i', $id);
$stm->execute();
$livro = $stm->get_result()->fetch_assoc();

if (!$livro) {
    die('Livro não encontrado.');
}

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

        $sql = 'UPDATE livros SET titulo=?, autor=?, editora=?, isbn=?, genero=?, ano_publicacao=?, quantidade_exemplares=? WHERE id=?';

        $stm = $conexao->prepare($sql);
        $stm->bind_param('sssssiii', $titulo, $autor, $editora, $isbn, $genero, $ano, $quantidade, $id);

        if ($stm->execute()) {
            header('Location: livros.php');
            exit;
        }

        $erro = 'Erro ao atualizar o livro.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar livro</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class="caixa pequena">
        <h1>Atualizar livro</h1>
        
        <?php if (!empty($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input
                type="hidden"
                name="id"
                value="<?php echo $id; ?>"
            >

            <label>Título</label>
            <input
                name="titulo"
                value="<?php echo htmlspecialchars($livro['titulo']); ?>"
                required
            >

            <label>Autor</label>
            <input
                name="autor"
                value="<?php echo htmlspecialchars($livro['autor']); ?>"
                required
            >

            <label>Editora</label>
            <input
                name="editora"
                value="<?php echo htmlspecialchars($livro['editora'] ?? ''); ?>"
            >

            <label>ISBN</label>
            <input
                name="isbn"
                maxlength="13"
                pattern="[0-9]{10}|[0-9]{13}"
                title="Digite 10 ou 13 números"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                value="<?php echo htmlspecialchars($livro['isbn'] ?? ''); ?>"
            >
            <label>Gênero</label>
            <input
                name="genero"
                value="<?php echo htmlspecialchars($livro['genero']); ?>"
                required
            >
            <label>Ano</label>
            <input
                type="number"
                name="ano"
                value="<?php echo $livro['ano_publicacao']; ?>"
            >

            <label>Quantidade</label>
            <input
                type="number"
                name="quantidade"
                min="1"
                value="<?php echo $livro['quantidade_exemplares']; ?>"
                required
            >

            <div class="botoes">
                <button type="submit">Atualizar</button>
                <a href="livros.php">Cancelar</a>
            </div>
        </form>
    </main>
</body>

</html>