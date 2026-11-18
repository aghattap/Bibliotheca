<?php

session_start();

require 'conexao.php';

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$stm = $conexao->prepare('SELECT id, nome, senha FROM bibliotecarios WHERE email = ?');
$stm->bind_param('s', $email);
$stm->execute();
$usuario = $stm->get_result()->fetch_assoc();

if ($usuario && password_verify($senha, $usuario['senha'])) {
    $_SESSION['bibliotecario'] = [
        'id' => $usuario['id'], 
        'nome' => $usuario['nome']
    ];
    
    header('Location: menu.php');
} else {
    header('Location: index.html?erro=1');
}

exit;