<?php
session_start();

if (isset($_SESSION["id_usuario"])) {
    header("Location: index.php");
    exit;
}

require __DIR__ . "/php/conexao.php";

$erroLogin = "";
$identificacao = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identificacao = trim($_POST["identificacao"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if (!$conexao) {
        $erroLogin = $erroConexao;
    } elseif ($identificacao === "" || $senha === "") {
        $erroLogin = "Preencha seu e-mail ou nick e a senha.";
    } else {
        $stmt = $conexao->prepare("SELECT id_usuario, nick, senha FROM usuarios WHERE email = ? OR nick = ? LIMIT 1");
        $stmt->bind_param("ss", $identificacao, $identificacao);
        $stmt->execute();
        $stmt->store_result();

        $idUsuarioEncontrado = null;
        $nickEncontrado = null;
        $senhaHashEncontrada = null;

        $stmt->bind_result($idUsuarioEncontrado, $nickEncontrado, $senhaHashEncontrada);
        $usuarioEncontrado = $stmt->fetch();
        $stmt->close();

        if ($usuarioEncontrado && password_verify($senha, $senhaHashEncontrada)) {
            session_regenerate_id(true);
            $_SESSION["id_usuario"] = (int) $idUsuarioEncontrado;
            $_SESSION["nick"] = $nickEncontrado;
            $_SESSION["flash"] = ["tipo" => "sucesso", "texto" => "Login realizado. Bem-vindo de volta, " . $nickEncontrado . "."];
            header("Location: index.php");
            exit;
        }

        $erroLogin = "E-mail, nick ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | Vortex</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Oxanium:wght@400;500;600;700&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/login.css?v=20260810c">
</head>
<body>
    <main class="login-container">
        <img src="fotos/ftarena1.png" class="fundo-login" alt="">
        <div class="fundo-overlay"></div>

        <section class="login-box">
            <div class="barra-topo"></div>

            <p class="vortex-access">VORTEX ACCESS</p>
            <h1>ENTRAR NA VORTEX</h1>

            <p class="subtitulo">
                Acesse sua conta e volte para a competição.
            </p>

            <?php if ($erroLogin): ?>
                <div class="mensagem-form erro-form"><?= htmlspecialchars($erroLogin) ?></div>
            <?php endif; ?>

            <form id="form-login" method="post">
                <div class="campo">
                    <label for="identificacao">E-mail ou Nick</label>
                    <input type="text" id="identificacao" name="identificacao" placeholder="Digite seu e-mail ou nickname" autocomplete="username" value="<?= htmlspecialchars($identificacao) ?>" required>
                </div>

                <div class="campo">
                    <label for="senha">Senha</label>
                    <div class="campo-senha">
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" autocomplete="current-password" required>
                        <button type="button" class="mostrar-senha" aria-label="Mostrar senha">MOSTRAR</button>
                    </div>
                </div>

                <div class="opcoes-login">
                    <label class="lembrar-login">
                        <input type="checkbox" name="lembrar">
                        <span>Lembrar-me</span>
                    </label>

                    <button type="button" class="esqueci-senha" id="esqueci-senha">
                        Esqueci minha senha
                    </button>
                </div>

                <button type="submit" class="botao-login">
                    ENTRAR
                    <span>→</span>
                </button>
            </form>

            <div class="separador"><span>OU</span></div>

            <p class="criar-conta-login">
                Ainda não possui uma conta?
                <a href="cadastro.php">Crie agora</a>
            </p>
        </section>
    </main>

    <aside class="sidebar">
        <button class="sidebar-logo" type="button" aria-label="Abrir menu">
            <img src="fotos/Vortexv3.png" alt="Logo Vortex">
        </button>

        <nav class="sidebar-menu">
            <a href="index.php">INÍCIO</a>
            <a href="index.php#campeonatos">CAMPEONATOS</a>
            <a href="index.php#inicio">SOBRE A VORTEX</a>
        </nav>
    </aside>

    <script>
        const senha = document.querySelector("#senha");
        const mostrarSenha = document.querySelector(".mostrar-senha");
        const esqueciSenha = document.querySelector("#esqueci-senha");
        const sidebar = document.querySelector(".sidebar");
        const botaoLogo = document.querySelector(".sidebar-logo");

        mostrarSenha.addEventListener("click", function () {
            const estaOculta = senha.type === "password";
            senha.type = estaOculta ? "text" : "password";
            mostrarSenha.textContent = estaOculta ? "OCULTAR" : "MOSTRAR";
        });

        esqueciSenha.addEventListener("click", function () {
            alert("A recuperação de senha será adicionada em breve.");
        });

        botaoLogo.addEventListener("click", function () {
            sidebar.classList.toggle("aberta");
        });
    </script>
</body>
</html>
