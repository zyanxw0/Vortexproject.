<?php

$dbHost = "sql302.byethost9.com";
$dbUsuario = "b9_42623712";
$dbSenha = "4301gamer";
$dbNome = "b9_42623712_vortex";

$conexao = new mysqli($dbHost, $dbUsuario, $dbSenha, $dbNome);

if ($conexao->connect_error) {
    die("Erro na conexao com o banco de dados.");
}

$conexao->set_charset("utf8mb4");
?>