<?php
session_start();

require_once 'conexao.php';

if (empty($_SESSION['bibliotecario'])) {
    header('Location: index.html');
    exit;
}
