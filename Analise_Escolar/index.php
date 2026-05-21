<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema Escolar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <div class="cabecalho">
        <h1>Sistema de Análise Escolar</h1>
        <p>Análise estatística de desempenho de turma</p>
    </div>

    <div class="corpo">
        <p class="titulo-secao">Informações da Turma</p>

        <!-- O formulário envia os dados para alunos.php usando o método POST -->
        <form action="alunos.php" method="post">

            <div class="campo-grupo">
                <label>Nome da Turma:</label>
                <input type="text" name="nome_turma" required placeholder="Ex: 3º Ano A">
            </div>

            <div class="campo-grupo">
                <label>Quantidade de Alunos:</label>
                <input type="number" name="qtd_alunos" required min="2" max="30" placeholder="De 2 a 30 alunos">
            </div>

            <button type="submit" class="botao">Avançar →</button>

        </form>
    </div>

</div>
</body>
</html>