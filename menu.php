<?php 

require 'autenticacao.php'; 

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Biblioteca</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class="caixa pequena">
        <h1>Biblioteca</h1>
        <p>Olá, bibliotecário!</p>
        <h2>Menu</h2>
        <nav>
            <a href="livros.php">Livros</a>
            <a href="clientes.php">Leitores</a>
            <a href="emprestimos.php">Empréstimos</a>
            <a href="logout.php">Sair</a>
        </nav>
    </main>
</body>

</html>