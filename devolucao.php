<?php

require 'autenticacao.php';

$id = (int) ($_GET['id'] ?? 0);
$data = date('Y-m-d');

$sql = 'UPDATE emprestimos SET data_devolucao = ? WHERE id = ? AND data_devolucao IS NULL';
$stm = $conexao->prepare($sql);
$stm->bind_param('si', $data, $id);

if ($stm->execute()) {
    header('Location: emprestimos.php');
    exit;
}

die('Erro ao registrar a devolução.');