<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../perfil.php");
    exit;
}

require __DIR__ . "/conexao.php";

if (!$conexao) {
    $_SESSION["flash"] = ["tipo" => "erro", "texto" => $erroConexao];
    header("Location: ../perfil.php");
    exit;
}

$idUsuario = (int) $_SESSION["id_usuario"];
$idCampeonato = filter_input(INPUT_POST, "id_campeonato", FILTER_VALIDATE_INT);

if (!$idCampeonato) {
    $_SESSION["flash"] = ["tipo" => "erro", "texto" => "Inscrição inválida."];
    header("Location: ../perfil.php");
    exit;
}

$stmt = $conexao->prepare("DELETE FROM inscricoes WHERE id_usuario = ? AND id_campeonato = ?");
$stmt->bind_param("ii", $idUsuario, $idCampeonato);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION["flash"] = ["tipo" => "sucesso", "texto" => "Inscrição cancelada."];
} else {
    $_SESSION["flash"] = ["tipo" => "aviso", "texto" => "Essa inscrição não foi encontrada."];
}

$stmt->close();
header("Location: ../perfil.php");
exit;
