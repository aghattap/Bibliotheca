<?php

require 'autenticacao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cliente = (int) $_POST['cliente'];
    $livro = (int) $_POST['livro'];
    $data = date('Y-m-d');
    $prazo = $_POST['prazo'];

    $sql = 'SELECT quantidade_exemplares - (
                SELECT COUNT(*)
                FROM emprestimos
                WHERE id_livro = livros.id
                AND data_devolucao IS NULL
            ) AS disponiveis
            FROM livros
            WHERE id = ?';

    $stm = $conexao->prepare($sql);
    $stm->bind_param('i', $livro);
    $stm->execute();
    $disponibilidade = $stm->get_result()->fetch_assoc();

    if (!$disponibilidade || $disponibilidade['disponiveis'] < 1) {
        $erro = 'Este livro não está disponível.';
    } else {
        $sql = 'INSERT INTO emprestimos (id_cliente, id_livro, data_emprestimo, prazo_devolucao) VALUES (?, ?, ?, ?)';

        $stm = $conexao->prepare($sql);
        $stm->bind_param('iiss', $cliente, $livro, $data, $prazo);

        if ($stm->execute()) {
            header('Location: emprestimos.php');
            exit;
        }

        $erro = 'Erro ao registrar o empréstimo.';
    }
}

$clientes = $conexao->query('SELECT id, nome FROM clientes ORDER BY nome');

$sql = 'SELECT l.id, l.titulo, l.quantidade_exemplares - (
            SELECT COUNT(*)
            FROM emprestimos e
            WHERE e.id_livro = l.id
            AND e.data_devolucao IS NULL
        ) AS disponiveis
        FROM livros l
        HAVING disponiveis > 0
        ORDER BY titulo';

$livros = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo empréstimo</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class="caixa pequena">
        <h1>Novo empréstimo</h1>

        <?php if ($erro): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Leitor</label>
            <select
                name="cliente"
                required
            >
                <option value="">Selecione</option>

                <?php while ($item = $clientes->fetch_assoc()): ?>
                    <option value="<?php echo $item['id']; ?>">
                        <?php echo htmlspecialchars($item['nome']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Livro</label>
            <select
                name="livro"
                required
            >
                <option value="">Selecione</option>

                <?php while ($item = $livros->fetch_assoc()): ?>
                    <option value="<?php echo $item['id']; ?>">
                        <?php echo htmlspecialchars($item['titulo']); ?>
                        (<?php echo $item['disponiveis']; ?> disponíveis)
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Prazo de devolução</label>
            <input
                type="date"
                name="prazo"
                min="<?php echo date('Y-m-d'); ?>"
                value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>"
                required
            >

            <div class="botoes">
                <button type="submit">Registrar</button>
                <a href="emprestimos.php">Cancelar</a>
            </div>
        </form>
    </main>
</body>

</html>