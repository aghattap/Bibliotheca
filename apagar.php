<?php

require 'autenticacao.php';

$id = (int) ($_GET['id'] ?? 0);
$stm = $conexao->prepare('DELETE FROM clientes WHERE id = ?');
$stm->bind_param('i', $id);

if (!$stm->execute()) {
    die('Não é possível excluir um leitor com empréstimos registrados. <a href="clientes.php">Voltar</a>');
}

header('Location: clientes.php');
exit;