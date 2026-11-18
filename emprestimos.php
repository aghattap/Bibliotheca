<?php

require 'autenticacao.php';

$sql = "SELECT e.*, c.nome, l.titulo FROM emprestimos e
        INNER JOIN clientes c ON c.id = e.id_cliente
        INNER JOIN livros l ON l.id = e.id_livro
        ORDER BY e.id DESC";

$resultado = $conexao->query($sql);
$hoje = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empréstimos</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class="caixa">
        <h1>Empréstimos</h1>

        <p><a href="cadastrar_emprestimo.php">Registrar novo empréstimo</a></p>

        <?php if ($resultado->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Livro</th>
                    <th>Leitor</th>
                    <th>Empréstimo</th>
                    <th>Prazo</th>
                    <th>Devolução</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>

                <?php while ($item = $resultado->fetch_assoc()):
                    if ($item['data_devolucao']) {
                        $status = 'Devolvido';
                    } elseif ($item['prazo_devolucao'] < $hoje) {
                        $status = 'Atrasado';
                    } else {
                        $status = 'Ativo';
                    }
                    ?>

                    <tr>
                        <td><?php echo htmlspecialchars($item['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($item['nome']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($item['data_emprestimo'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($item['prazo_devolucao'])); ?></td>
                        <td><?php echo $item['data_devolucao'] ? date('d/m/Y', strtotime($item['data_devolucao'])) : '-'; ?></td>
                        <td><?php echo $status; ?></td>

                        <td>
                            <?php if (!$item['data_devolucao']): ?>
                                <a
                                    href="devolucao.php?id=<?php echo $item['id']; ?>"
                                    onclick="return confirm('Confirmar devolução?')"
                                >
                                    Devolver
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Nenhum empréstimo registrado.</p>
        <?php endif; ?>

        <div class="botoes">
            <a href="menu.php">Voltar</a>
        </div>
    </main>
</body>

</html>