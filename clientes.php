<?php

require 'autenticacao.php';

$resultado = $conexao->query('SELECT * FROM clientes ORDER BY nome');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leitores</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class="caixa">
        <h1>Leitores</h1>

        <p><a href="cadastro.php">Cadastrar leitor</a></p>

        <?php if ($resultado->num_rows > 0): ?>

            <table>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>

                <?php while ($leitor = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($leitor['nome']); ?></td>
                        <td><?php echo htmlspecialchars($leitor['email']); ?></td>
                        <td><?php echo htmlspecialchars($leitor['telefone']); ?></td>
                        <td class="acoes">
                            
                            <a href="atualizar.php?id=<?php echo $leitor['id']; ?>">
                                Editar
                            </a>

                            <a 
                                href="apagar.php?id=<?php echo $leitor['id']; ?>" 
                                onclick="return confirm('Excluir este leitor?')"
                            >
                                Excluir
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Nenhum leitor cadastrado.</p>
        <?php endif; ?>

        <div class="botoes">
            <a href="menu.php">Voltar</a>
        </div>
    </main>
</body>

</html>