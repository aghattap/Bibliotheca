<?php

require 'autenticacao.php';

$id = (int) ($_GET['id'] ?? 0);
$stm = $conexao->prepare('DELETE FROM livros WHERE id = ?');
$stm->bind_param('i', $id);

if (!$stm->execute()) {
    die('Não é possível excluir um livro com empréstimos registrados. <a href="livros.php">Voltar</a>');
}

header('Location: livros.php');
exit;