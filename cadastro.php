<?php
session_start();

if (isset($_SESSION["id_usuario"])) {
    header("Location: index.php");
    exit;
}

require __DIR__ . "/php/conexao.php";

$avisoCompetir = isset($_GET["aviso"]) && $_GET["aviso"] === "competir";
$erroCadastro = "";
$nome = "";
$nick = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $nick = trim($_POST["nick"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $confirmarSenha = $_POST["confirmar_senha"] ?? "";

    if (!$conexao) {
        $erroCadastro = $erroConexao;
    } elseif ($nome === "" || $nick === "" || $email === "" || $senha === "" || $confirmarSenha === "") {
        $erroCadastro = "Preencha todos os campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erroCadastro = "Digite um e-mail válido.";
    } elseif (strlen($senha) < 8) {
        $erroCadastro = "A senha precisa ter pelo menos 8 caracteres.";
    } elseif ($senha !== $confirmarSenha) {
        $erroCadastro = "As senhas não coincidem.";
    } else {
        $stmt = $conexao->prepare("SELECT id_usuario FROM usuarios WHERE email = ? OR nick = ? LIMIT 1");
        $stmt->bind_param("ss", $email, $nick);
        $stmt->execute();
        $stmt->store_result();
        $usuarioExistente = $stmt->num_rows > 0;
        $stmt->close();

        if ($usuarioExistente) {
            $erroCadastro = "Esse e-mail ou nick já está cadastrado.";
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conexao->prepare("INSERT INTO usuarios (nome, nick, email, senha) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nome, $nick, $email, $senhaHash);

            if ($stmt->execute()) {
                session_regenerate_id(true);
                $_SESSION["id_usuario"] = $stmt->insert_id;
                $_SESSION["nick"] = $nick;
                $_SESSION["flash"] = ["tipo" => "sucesso", "texto" => "Conta criada! Bem-vindo à Vortex, " . $nick . "."];
                $stmt->close();
                header("Location: index.php");
                exit;
            }

            $erroCadastro = "Não foi possível criar sua conta. Tente novamente.";
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta | Vortex</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Oxanium:wght@400;500;600;700&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/cadastro.css?v=20260810c">
</head>
<body>
    <main class="cadastro-container">
        <img src="fotos/vortexjogaddores1.png" class="fundo-cadastro" alt="">
        <div class="fundo-overlay"></div>

        <section class="cadastro-box">
            <div class="barra-topo"></div>

            <p class="vortex-access">VORTEX ACCESS</p>
            <h1>COMECE AGORA</h1>

            <p class="subtitulo">
                Crie sua conta, monte sua equipe e entre para o competitivo.
            </p>

            <?php if ($avisoCompetir): ?>
                <div class="aviso-competir">
                    <strong>Calma lá, campeão!</strong>
                    <p>Antes faça seu cadastro para poder começar a competir.</p>
                    <a href="login.php">Já possui uma conta? Entrar</a>
                </div>
            <?php endif; ?>

            <?php if ($erroCadastro): ?>
                <div class="mensagem-form erro-form"><?= htmlspecialchars($erroCadastro) ?></div>
            <?php endif; ?>

            <form id="form-cadastro" method="post">
                <div class="campo">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Seu nome completo" autocomplete="name" value="<?= htmlspecialchars($nome) ?>" required>
                </div>

                <div class="campo">
                    <label for="nick">Nick</label>
                    <input type="text" id="nick" name="nick" placeholder="Seu nickname" autocomplete="nickname" value="<?= htmlspecialchars($nick) ?>" required>
                </div>

                <div class="campo">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="Seu endereço de e-mail" autocomplete="email" value="<?= htmlspecialchars($email) ?>" required>
                </div>

                <div class="campo">
                    <label for="senha">Senha</label>
                    <div class="campo-senha">
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" autocomplete="new-password" minlength="8" required>
                        <button type="button" class="mostrar-senha" data-alvo="senha" aria-label="Mostrar senha">MOSTRAR</button>
                    </div>
                    <div class="forca-senha" aria-live="polite">
                        <div class="forca-barra"><span id="forca-preenchimento"></span></div>
                        <span id="forca-texto">Use pelo menos 8 caracteres.</span>
                    </div>
                </div>

                <div class="campo">
                    <label for="confirmar-senha">Confirmar senha</label>
                    <div class="campo-senha">
                        <input type="password" id="confirmar-senha" name="confirmar_senha" placeholder="Digite sua senha novamente" autocomplete="new-password" minlength="8" required>
                        <button type="button" class="mostrar-senha" data-alvo="confirmar-senha" aria-label="Mostrar confirmação da senha">MOSTRAR</button>
                    </div>
                    <p class="erro-senha" id="erro-senha" aria-live="polite"></p>
                </div>

                <button type="submit" class="botao-cadastro">
                    CRIAR CONTA
                    <span>→</span>
                </button>
            </form>

            <div class="separador"><span>OU</span></div>

            <p class="possui-conta">
                Já possui uma conta?
                <a href="login.php">Entrar na Vortex</a>
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
        const form = document.querySelector("#form-cadastro");
        const senha = document.querySelector("#senha");
        const confirmarSenha = document.querySelector("#confirmar-senha");
        const erroSenha = document.querySelector("#erro-senha");
        const forcaPreenchimento = document.querySelector("#forca-preenchimento");
        const forcaTexto = document.querySelector("#forca-texto");
        const botoesMostrarSenha = document.querySelectorAll(".mostrar-senha");
        const sidebar = document.querySelector(".sidebar");
        const botaoLogo = document.querySelector(".sidebar-logo");

        botoesMostrarSenha.forEach(function (botao) {
            botao.addEventListener("click", function () {
                const campo = document.querySelector("#" + botao.dataset.alvo);
                const estaOculta = campo.type === "password";
                campo.type = estaOculta ? "text" : "password";
                botao.textContent = estaOculta ? "OCULTAR" : "MOSTRAR";
            });
        });

        senha.addEventListener("input", function () {
            const valor = senha.value;
            let nivel = 0;

            if (valor.length >= 8) nivel++;
            if (/[A-Za-z]/.test(valor) && /[0-9]/.test(valor)) nivel++;
            if (/[A-Z]/.test(valor) && /[^A-Za-z0-9]/.test(valor) && valor.length >= 10) nivel++;

            forcaPreenchimento.className = "";

            if (valor.length === 0) {
                forcaPreenchimento.style.width = "0%";
                forcaTexto.textContent = "Use pelo menos 8 caracteres.";
                return;
            }

            if (nivel <= 1) {
                forcaPreenchimento.style.width = "33%";
                forcaPreenchimento.classList.add("fraca");
                forcaTexto.textContent = "Senha fraca";
            } else if (nivel === 2) {
                forcaPreenchimento.style.width = "66%";
                forcaPreenchimento.classList.add("media");
                forcaTexto.textContent = "Senha média";
            } else {
                forcaPreenchimento.style.width = "100%";
                forcaPreenchimento.classList.add("forte");
                forcaTexto.textContent = "Senha forte";
            }
        });

        form.addEventListener("submit", function (event) {
            if (senha.value !== confirmarSenha.value) {
                event.preventDefault();
                erroSenha.textContent = "As senhas não coincidem.";
                confirmarSenha.focus();
            } else {
                erroSenha.textContent = "";
            }
        });

        confirmarSenha.addEventListener("input", function () {
            if (confirmarSenha.value === "" || confirmarSenha.value === senha.value) {
                erroSenha.textContent = "";
            }
        });

        botaoLogo.addEventListener("click", function () {
            sidebar.classList.toggle("aberta");
        });
    </script>
</body>
</html>
