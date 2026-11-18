<?php

require 'autenticacao.php';

$sql = 'SELECT l.*, l.quantidade_exemplares - (
            SELECT COUNT(*)
            FROM emprestimos e
            WHERE e.id_livro = l.id
            AND e.data_devolucao IS NULL
        ) AS disponiveis
        FROM livros l
        ORDER BY titulo';

$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livros</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class="caixa">
        <h1>Livros</h1>

        <p><a href="cadastrar_livro.php">Cadastrar livro</a></p>

        <?php if ($resultado->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Gênero</th>
                    <th>Editora</th>
                    <th>Ano</th>
                    <th>Disponíveis</th>
                    <th>Ações</th>
                </tr>

                <?php while ($livro = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($livro['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($livro['autor']); ?></td>
                        <td><?php echo htmlspecialchars($livro['genero']); ?></td>
                        <td><?php echo htmlspecialchars($livro['editora'] ?? ''); ?></td>
                        <td><?php echo $livro['ano_publicacao']; ?></td>
                        <td><?php echo $livro['disponiveis'] . ' de ' . $livro['quantidade_exemplares']; ?></td>

                        <td class="acoes">
                            <a href="atualizar_livro.php?id=<?php echo $livro['id']; ?>">
                                Editar
                            </a>

                            <a
                                href="apagar_livro.php?id=<?php echo $livro['id']; ?>"
                                onclick="return confirm('Excluir este livro?')"
                            >
                                Excluir
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Nenhum livro cadastrado.</p>
        <?php endif; ?>

        <div class="botoes">
            <a href="menu.php">Voltar</a>
        </div>
    </main>
</body>

</html>