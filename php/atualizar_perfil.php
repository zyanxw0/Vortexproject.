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
$nome = trim($_POST["nome"] ?? "");
$nick = trim($_POST["nick"] ?? "");
$email = trim($_POST["email"] ?? "");

if ($nome === "" || $nick === "" || $email === "") {
    $_SESSION["flash"] = ["tipo" => "erro", "texto" => "Preencha todos os campos do perfil."];
    header("Location: ../perfil.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["flash"] = ["tipo" => "erro", "texto" => "Digite um e-mail válido."];
    header("Location: ../perfil.php");
    exit;
}

$stmt = $conexao->prepare("SELECT id_usuario FROM usuarios WHERE (email = ? OR nick = ?) AND id_usuario <> ? LIMIT 1");
$stmt->bind_param("ssi", $email, $nick, $idUsuario);
$stmt->execute();
$stmt->store_result();
$duplicado = $stmt->num_rows > 0;
$stmt->close();

if ($duplicado) {
    $_SESSION["flash"] = ["tipo" => "erro", "texto" => "Esse e-mail ou nick já está sendo usado."];
    header("Location: ../perfil.php");
    exit;
}

$stmt = $conexao->prepare("UPDATE usuarios SET nome = ?, nick = ?, email = ? WHERE id_usuario = ?");
$stmt->bind_param("sssi", $nome, $nick, $email, $idUsuario);

if ($stmt->execute()) {
    $_SESSION["nick"] = $nick;
    $_SESSION["flash"] = ["tipo" => "sucesso", "texto" => "Perfil atualizado com sucesso."];
} else {
    $_SESSION["flash"] = ["tipo" => "erro", "texto" => "Não foi possível atualizar o perfil."];
}

$stmt->close();
header("Location: ../perfil.php");
exit;
