<?php
session_start();
$usuarioLogado = isset($_SESSION["id_usuario"]);
$nickUsuario = $_SESSION["nick"] ?? "Jogador";
$flash = $_SESSION["flash"] ?? null;
unset($_SESSION["flash"]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vortex Hub</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Oxanium:wght@400;500;600;700&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css?v=20260810c">
    <link rel="stylesheet" href="css/navbar.css?v=20260810c">
</head>
<body>
    <header>
        <nav class="navbar">
            <a href="#inicio" class="logo-navbar">
                <img src="fotos/vortex2.png" alt="Logo Vortex">
            </a>

            <ul class="menu-navbar">
                <li><a href="#inicio">INICIO</a></li>
                <li><a href="#campeonatos">CAMPEONATOS</a></li>
                <li><a href="#inicio">SOBRE NOS</a></li>
            </ul>

            <div class="conta-navbar">
                <?php if ($usuarioLogado): ?>
                    <a href="perfil.php" class="usuario-navbar">
                        <span class="status-usuario"></span>
                        <?= htmlspecialchars($nickUsuario) ?>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="entrar">ENTRAR</a>
                    <a href="cadastro.php" class="criar-conta">CRIAR CONTA</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <?php if ($flash): ?>
        <div class="flash-vortex <?= htmlspecialchars($flash["tipo"]) ?>" id="flash-vortex">
            <?= htmlspecialchars($flash["texto"]) ?>
        </div>
    <?php endif; ?>

    <main>
        <section class="inicio" id="inicio">
            <img src="fotos/fundo1.jpg" class="secao-inicio" alt="">

            <div class="inicio-conteudo">
                <div class="area-logo">
                    <img src="fotos/Vortexv3.png" class="vortex" alt="Vortex">
                </div>

                <div class="linha-vertical"></div>

                <div class="area-texto">
                    <div class="titulo-vortex">
                        <p class="sobrenos">O QUE É A</p>
                        <p class="vortexx">VORTEX?</p>
                    </div>

                    <div class="descricao-container">
                        <p class="descricao-vortex">A VORTEX É UMA PLATAFORMA DE CAMPEONATOS DE</p>
                        <p class="texto-JOGOS-COMPETITIVOS-">JOGOS COMPETITIVOS</p>
                        <p class="texto-inscrevase">COMECE A COMPETIR</p>
                    </div>

                    <div class="botoes-vortex">
                        <a href="#campeonatos" class="botao-campeonatos">EXPLORAR CAMPEONATOS</a>
                        <a href="#campeonatos" class="botao-conhecer">CONHECER A VORTEX</a>
                    </div>
                </div>
            </div>

            <a href="#campeonatos" class="seta-baixo" aria-label="Ir para campeonatos">
                <img src="fotos/seta.png" alt="Seta para baixo">
            </a>
        </section>

        <section id="campeonatos" class="campeonatos">
            <div class="campeonatos-conteudo">
                <div class="campeonatos-introducao">
                    <p class="campeonatos-tag">CAMPEONATOS VORTEX</p>

                    <h2>
                        NÃO PRECISA SER PRO<br>
                        PARA COMPETIR.
                    </h2>

                    <p class="campeonatos-descricao">
                        Os campeonatos da Vortex são competições amadoras para quem quer entrar no competitivo, jogar em equipe e se divertir. Monte seu time, escolha seu campeonato e entre na disputa.
                    </p>

                    <div class="campeonatos-destaques">
                        <span>CAMPEONATOS ONLINE</span>
                        <span>INSCRIÇÃO GRATUITA</span>
                        <span>PREMIAÇÕES SIMPLES</span>
                    </div>
                </div>

                <div class="area-vortexcampeoes">
                    <img src="fotos/jogadoresdavortex.png" class="vortexcampeoes" alt="Jogadores em campeonato de e-sports">
                </div>
            </div>

            <div class="como-funciona">
                <div class="titulo-secao-campeonatos">
                    <p>COMO FUNCIONA?</p>
                    <h3>DA EQUIPE ATÉ A DISPUTA</h3>
                    <span>Participar é simples. Você só precisa de uma equipe, escolher um campeonato e garantir sua vaga.</span>
                </div>

                <div class="etapas-campeonato">
                    <article class="etapa-card">
                        <span class="etapa-numero">01</span>
                        <h4>MONTE SUA EQUIPE</h4>
                        <p>Crie seu time e chame seus amigos para competir com você.</p>
                    </article>

                    <article class="etapa-card">
                        <span class="etapa-numero">02</span>
                        <h4>ESCOLHA UM CAMPEONATO</h4>
                        <p>Veja as competições disponíveis e escolha o jogo da sua equipe.</p>
                    </article>

                    <article class="etapa-card">
                        <span class="etapa-numero">03</span>
                        <h4>FAÇA SUA INSCRIÇÃO</h4>
                        <p>Garanta a vaga antes do limite de equipes ser atingido.</p>
                    </article>

                    <article class="etapa-card">
                        <span class="etapa-numero">04</span>
                        <h4>ENTRE NA DISPUTA</h4>
                        <p>Enfrente outras equipes e avance pelas partidas do campeonato.</p>
                    </article>
                </div>
            </div>

            <div class="campeonatos-disponiveis">
                <div class="titulo-secao-campeonatos">
                    <p>COMPETIÇÕES ABERTAS</p>
                    <h3>ESCOLHA SEU CAMPEONATO</h3>
                    <span>Comece por uma das competições da Vortex.</span>
                </div>

                <div class="cards-campeonatos">
                    <article class="campeonato-card valorant-card">
                        <div class="campeonato-topo">
                            <span class="campeonato-status aberto">INSCRIÇÕES ABERTAS</span>
                            <span class="campeonato-jogo">VALORANT</span>
                        </div>

                        <div class="campeonato-corpo">
                            <p class="campeonato-edicao">VORTEX VALORANT #01</p>
                            <h4>VORTEX VALORANT CUP</h4>
                            <p class="campeonato-resumo">Torneio 5v5 para equipes que querem começar a competir.</p>

                            <div class="campeonato-info">
                                <div><span>FORMATO</span><strong>5v5</strong></div>
                                <div><span>EQUIPES</span><strong>8</strong></div>
                                <div><span>INSCRIÇÃO</span><strong>GRÁTIS</strong></div>
                                <div><span>PREMIAÇÃO</span><strong>R$ 100</strong></div>
                            </div>

                            <?php if ($usuarioLogado): ?>
                                <button type="button" class="participar-campeonato abrir-confirmacao" data-id="1" data-nome="VORTEX VALORANT CUP">PARTICIPAR</button>
                            <?php else: ?>
                                <a href="cadastro.php?aviso=competir" class="participar-campeonato precisa-cadastro">PARTICIPAR</a>
                            <?php endif; ?>
                        </div>
                    </article>

                    <article class="campeonato-card cs2-card">
                        <div class="campeonato-topo">
                            <span class="campeonato-status aberto">INSCRIÇÕES ABERTAS</span>
                            <span class="campeonato-jogo">COUNTER-STRIKE 2</span>
                        </div>

                        <div class="campeonato-corpo">
                            <p class="campeonato-edicao">VORTEX CS2 #01</p>
                            <h4>VORTEX CS2 CLASH</h4>
                            <p class="campeonato-resumo">Disputas rápidas em 5v5 com chaveamento para oito equipes.</p>

                            <div class="campeonato-info">
                                <div><span>FORMATO</span><strong>5v5</strong></div>
                                <div><span>EQUIPES</span><strong>8</strong></div>
                                <div><span>INSCRIÇÃO</span><strong>GRÁTIS</strong></div>
                                <div><span>PREMIAÇÃO</span><strong>R$ 100</strong></div>
                            </div>

                            <?php if ($usuarioLogado): ?>
                                <button type="button" class="participar-campeonato abrir-confirmacao" data-id="2" data-nome="VORTEX CS2 CLASH">PARTICIPAR</button>
                            <?php else: ?>
                                <a href="cadastro.php?aviso=competir" class="participar-campeonato precisa-cadastro">PARTICIPAR</a>
                            <?php endif; ?>
                        </div>
                    </article>

                    <article class="campeonato-card lol-card">
                        <div class="campeonato-topo">
                            <span class="campeonato-status em-breve">EM BREVE</span>
                            <span class="campeonato-jogo">LEAGUE OF LEGENDS</span>
                        </div>

                        <div class="campeonato-corpo">
                            <p class="campeonato-edicao">VORTEX RIFT #01</p>
                            <h4>VORTEX RIFT CUP</h4>
                            <p class="campeonato-resumo">Uma competição 5v5 para equipes prontas para dominar o Rift.</p>

                            <div class="campeonato-info">
                                <div><span>FORMATO</span><strong>5v5</strong></div>
                                <div><span>EQUIPES</span><strong>8</strong></div>
                                <div><span>INSCRIÇÃO</span><strong>GRÁTIS</strong></div>
                                <div><span>PREMIAÇÃO</span><strong>R$ 150</strong></div>
                            </div>

                            <button type="button" class="participar-campeonato campeonato-indisponivel" disabled>EM BREVE</button>
                        </div>
                    </article>
                </div>

                <p class="aviso-login-campeonato">
                    Para participar de um campeonato, o jogador precisa ter uma conta na Vortex.
                </p>
            </div>
        </section>
    </main>

    <div class="modal-confirmacao" id="modal-confirmacao" aria-hidden="true">
        <div class="modal-overlay" data-fechar-modal></div>
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="titulo-modal">
            <p class="modal-tag">CONFIRMAÇÃO DE INSCRIÇÃO</p>
            <h3 id="titulo-modal">ENTRAR NO CAMPEONATO?</h3>
            <p>Você está prestes a se inscrever em <strong id="nome-campeonato-modal"></strong>. Essa inscrição ficará salva no seu perfil.</p>

            <form action="php/inscrever.php" method="post" class="modal-acoes">
                <input type="hidden" name="id_campeonato" id="id-campeonato-modal">
                <button type="button" class="botao-cancelar-modal" data-fechar-modal>CANCELAR</button>
                <button type="submit" class="botao-confirmar-modal">CONFIRMAR INSCRIÇÃO</button>
            </form>
        </div>
    </div>

    <script>
        const botoesSemConta = document.querySelectorAll(".precisa-cadastro");
        const botoesConfirmacao = document.querySelectorAll(".abrir-confirmacao");
        const modal = document.querySelector("#modal-confirmacao");
        const idCampeonatoModal = document.querySelector("#id-campeonato-modal");
        const nomeCampeonatoModal = document.querySelector("#nome-campeonato-modal");
        const botoesFecharModal = document.querySelectorAll("[data-fechar-modal]");
        const flash = document.querySelector("#flash-vortex");

        botoesSemConta.forEach(function (botao) {
            botao.addEventListener("click", function () {
                alert("Calma lá, campeão! Antes faça seu cadastro para poder começar a competir.");
            });
        });

        botoesConfirmacao.forEach(function (botao) {
            botao.addEventListener("click", function () {
                idCampeonatoModal.value = botao.dataset.id;
                nomeCampeonatoModal.textContent = botao.dataset.nome;
                modal.classList.add("aberto");
                modal.setAttribute("aria-hidden", "false");
                document.body.classList.add("modal-aberto");
            });
        });

        botoesFecharModal.forEach(function (botao) {
            botao.addEventListener("click", fecharModal);
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") fecharModal();
        });

        function fecharModal() {
            modal.classList.remove("aberto");
            modal.setAttribute("aria-hidden", "true");
            document.body.classList.remove("modal-aberto");
        }

        if (flash) {
            setTimeout(function () {
                flash.classList.add("sumindo");
            }, 4200);
        }
    </script>
</body>
</html>
