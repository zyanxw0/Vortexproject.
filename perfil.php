<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

require __DIR__ . "/php/conexao.php";

if (!$conexao) {
    die(htmlspecialchars($erroConexao));
}

$idUsuario = (int) $_SESSION["id_usuario"];
$flash = $_SESSION["flash"] ?? null;
unset($_SESSION["flash"]);

$stmt = $conexao->prepare("SELECT id_usuario, nome, nick, email, data_cadastro FROM usuarios WHERE id_usuario = ? LIMIT 1");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$stmt->store_result();

$idUsuarioBanco = null;
$nomeBanco = null;
$nickBanco = null;
$emailBanco = null;
$dataCadastroBanco = null;

$stmt->bind_result($idUsuarioBanco, $nomeBanco, $nickBanco, $emailBanco, $dataCadastroBanco);
$usuarioEncontrado = $stmt->fetch();
$stmt->close();

if (!$usuarioEncontrado) {
    $_SESSION = [];
    session_destroy();
    header("Location: login.php");
    exit;
}

$usuario = [
    "id_usuario" => $idUsuarioBanco,
    "nome" => $nomeBanco,
    "nick" => $nickBanco,
    "email" => $emailBanco,
    "data_cadastro" => $dataCadastroBanco
];

$stmt = $conexao->prepare("SELECT c.id_campeonato, c.nome, c.jogo, c.formato, c.premiacao, i.data_inscricao FROM inscricoes i INNER JOIN campeonatos c ON c.id_campeonato = i.id_campeonato WHERE i.id_usuario = ? ORDER BY i.data_inscricao DESC");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$stmt->store_result();

$idCampeonato = null;
$nomeCampeonato = null;
$jogoCampeonato = null;
$formatoCampeonato = null;
$premiacaoCampeonato = null;
$dataInscricao = null;

$stmt->bind_result($idCampeonato, $nomeCampeonato, $jogoCampeonato, $formatoCampeonato, $premiacaoCampeonato, $dataInscricao);

$inscricoes = [];
while ($stmt->fetch()) {
    $inscricoes[] = [
        "id_campeonato" => $idCampeonato,
        "nome" => $nomeCampeonato,
        "jogo" => $jogoCampeonato,
        "formato" => $formatoCampeonato,
        "premiacao" => $premiacaoCampeonato,
        "data_inscricao" => $dataInscricao
    ];
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($usuario["nick"]) ?> | Vortex</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Oxanium:wght@400;500;600;700&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/navbar.css?v=20260810c">
    <link rel="stylesheet" href="css/perfil.css?v=20260810c">
</head>
<body>
    <header>
        <nav class="navbar">
            <a href="index.php" class="logo-navbar">
                <img src="fotos/vortex2.png" alt="Logo Vortex">
            </a>

            <ul class="menu-navbar">
                <li><a href="index.php">INICIO</a></li>
                <li><a href="index.php#campeonatos">CAMPEONATOS</a></li>
            </ul>

            <div class="conta-navbar">
                <a href="perfil.php" class="usuario-navbar ativo">
                    <span class="status-usuario"></span>
                    <?= htmlspecialchars($usuario["nick"]) ?>
                </a>
                <a href="php/logout.php" class="sair-navbar">SAIR</a>
            </div>
        </nav>
    </header>

    <?php if ($flash): ?>
        <div class="flash-perfil <?= htmlspecialchars($flash["tipo"]) ?>"><?= htmlspecialchars($flash["texto"]) ?></div>
    <?php endif; ?>

    <main class="perfil-container">
        <section class="perfil-cabecalho">
            <p class="perfil-tag">VORTEX PLAYER</p>
            <h1><?= htmlspecialchars($usuario["nick"]) ?></h1>
            <p><?= htmlspecialchars($usuario["nome"]) ?></p>

            <div class="perfil-estatisticas">
                <div>
                    <span>CAMPEONATOS</span>
                    <strong><?= count($inscricoes) ?></strong>
                </div>
                <div>
                    <span>MEMBRO DESDE</span>
                    <strong><?= date("d/m/Y", strtotime($usuario["data_cadastro"])) ?></strong>
                </div>
            </div>
        </section>

        <div class="perfil-grid">
            <section class="painel-perfil">
                <p class="painel-tag">DADOS DA CONTA</p>
                <h2>SEU PERFIL</h2>
                <p class="painel-descricao">Editar suas informações.</p>

                <form action="php/atualizar_perfil.php" method="post" class="form-perfil">
                    <label>
                        Nome
                        <input type="text" name="nome" value="<?= htmlspecialchars($usuario["nome"]) ?>" required>
                    </label>

                    <label>
                        Nick
                        <input type="text" name="nick" value="<?= htmlspecialchars($usuario["nick"]) ?>" required>
                    </label>

                    <label>
                        E-mail
                        <input type="email" name="email" value="<?= htmlspecialchars($usuario["email"]) ?>" required>
                    </label>

                    <button type="submit">SALVAR ALTERAÇÕES</button>
                </form>
            </section>

            <section class="painel-perfil campeonatos-perfil">
                <p class="painel-tag">MINHAS INSCRIÇÕES</p>
                <h2>CAMPEONATOS</h2>
                <p class="painel-descricao">Aqui aparecem os campeonatos em que sua conta está inscrita.</p>

                <?php if (!$inscricoes): ?>
                    <div class="sem-inscricoes">
                        <strong>NENHUMA INSCRIÇÃO AINDA</strong>
                        <p>Escolha um campeonato e confirme sua participação.</p>
                        <a href="index.php#campeonatos">VER CAMPEONATOS</a>
                    </div>
                <?php else: ?>
                    <div class="lista-inscricoes">
                        <?php foreach ($inscricoes as $inscricao): ?>
                            <article class="inscricao-card">
                                <div>
                                    <span><?= htmlspecialchars($inscricao["jogo"]) ?></span>
                                    <h3><?= htmlspecialchars($inscricao["nome"]) ?></h3>
                                    <p><?= htmlspecialchars($inscricao["formato"]) ?> · R$ <?= number_format((float) $inscricao["premiacao"], 0, ",", ".") ?> · Inscrito em <?= date("d/m/Y", strtotime($inscricao["data_inscricao"])) ?></p>
                                </div>

                                <form action="php/cancelar_inscricao.php" method="post" onsubmit="return confirm('Cancelar sua inscrição neste campeonato?');">
                                    <input type="hidden" name="id_campeonato" value="<?= (int) $inscricao["id_campeonato"] ?>">
                                    <button type="submit">CANCELAR</button>
                                </form>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>
</body>
</html>
