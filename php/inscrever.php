<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php#campeonatos");
    exit;
}

require __DIR__ . "/conexao.php";

if (!$conexao) {
    $_SESSION["flash"] = ["tipo" => "erro", "texto" => $erroConexao];
    header("Location: ../index.php#campeonatos");
    exit;
}

$idUsuario = (int) $_SESSION["id_usuario"];
$idCampeonato = filter_input(INPUT_POST, "id_campeonato", FILTER_VALIDATE_INT);

if (!$idCampeonato) {
    $_SESSION["flash"] = ["tipo" => "erro", "texto" => "Campeonato inválido."];
    header("Location: ../index.php#campeonatos");
    exit;
}

$stmt = $conexao->prepare("SELECT nome, status FROM campeonatos WHERE id_campeonato = ? LIMIT 1");
$stmt->bind_param("i", $idCampeonato);
$stmt->execute();
$stmt->store_result();
$nomeCampeonato = null;
$statusCampeonato = null;
$stmt->bind_result($nomeCampeonato, $statusCampeonato);
$campeonatoEncontrado = $stmt->fetch();
$stmt->close();

if (!$campeonatoEncontrado) {
    $_SESSION["flash"] = ["tipo" => "erro", "texto" => "Esse campeonato não existe."];
    header("Location: ../index.php#campeonatos");
    exit;
}

if ($statusCampeonato !== "aberto") {
    $_SESSION["flash"] = ["tipo" => "erro", "texto" => "As inscrições desse campeonato ainda não estão abertas."];
    header("Location: ../index.php#campeonatos");
    exit;
}

$stmt = $conexao->prepare("SELECT id_inscricao FROM inscricoes WHERE id_usuario = ? AND id_campeonato = ? LIMIT 1");
$stmt->bind_param("ii", $idUsuario, $idCampeonato);
$stmt->execute();
$stmt->store_result();
$jaInscrito = $stmt->num_rows > 0;
$stmt->close();

if ($jaInscrito) {
    $_SESSION["flash"] = ["tipo" => "aviso", "texto" => "Você já está inscrito nesse campeonato."];
    header("Location: ../index.php#campeonatos");
    exit;
}

$stmt = $conexao->prepare("INSERT INTO inscricoes (id_usuario, id_campeonato) VALUES (?, ?)");
$stmt->bind_param("ii", $idUsuario, $idCampeonato);

if ($stmt->execute()) {
    $_SESSION["flash"] = ["tipo" => "sucesso", "texto" => "Inscrição confirmada em " . $nomeCampeonato . "!"];
} else {
    $_SESSION["flash"] = ["tipo" => "erro", "texto" => "Não foi possível concluir sua inscrição."];
}

$stmt->close();
header("Location: ../index.php#campeonatos");
exit;
