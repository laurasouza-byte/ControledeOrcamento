<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Controle de Orçamento</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1>Controle de Orçamento</h1>
    <p><a href="index.php?pagina=lancamento">+ Novo lançamento</a></p>

    <?php if (isset($_GET['sucesso'])): ?>
        <p class="ok"><?= htmlspecialchars($_GET['sucesso']) ?></p>
    <?php endif; ?>

    <?php if (isset($_GET['erro'])): ?>
        <p class="erro"><?= htmlspecialchars($_GET['erro']) ?></p>
    <?php endif; ?>

    <div class="card">
        <h2>Resumo de <?= htmlspecialchars(substr($mesAno, 5, 2) . '/' . substr($mesAno, 0, 4)) ?></h2>
        <p>Receitas: <strong class="receita">R$ <?= number_format($resumo['total_receitas'], 2, ',', '.') ?></strong>
        </p>
        <p>Despesas: <strong class="despesa">R$ <?= number_format($resumo['total_despesas'], 2, ',', '.') ?></strong>
        </p>
        <p>Saldo: <strong class="<?= $resumo['saldo'] < 0 ? 'despesa' : '' ?>">R$
                <?= number_format($resumo['saldo'], 2, ',', '.') ?></strong></p>

        <?php if ($resumo['saldo'] < 0): ?>
            <p class="alerta">Atenção: o saldo deste mês está negativo.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Filtrar</h2>
        <form method="GET" action="index.php">
            <input type="hidden" name="pagina" value="home">
            <p>
                <label>Mês: <input type="month" name="mes_ano" value="<?= htmlspecialchars($mesAno) ?>"></label>
                <label>Categoria:
                    <select name="categoria">
                        <option value="">Todas</option>
                        <?php foreach ($categorias as $opcao): ?>
                            <option value="<?= htmlspecialchars($opcao) ?>" <?= $categoria === $opcao ? 'selected' : '' ?>>
                                <?= htmlspecialchars($opcao) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button type="submit">Filtrar</button>
                <a href="index.php?pagina=home">Limpar</a>
            </p>
        </form>
    </div>

    <div class="card">
        <h2>Lançamentos</h2>
        <?php if (empty($lancamentos)): ?>
            <p>Nenhum lançamento encontrado.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($lancamentos as $lancamento): ?>
                    <li>
                        <?= htmlspecialchars(date('d/m/Y', strtotime($lancamento['data']))) ?> —
                        <?= htmlspecialchars($lancamento['categoria']) ?> —
                        <?= $lancamento['tipo'] === 'receita' ? 'Receita' : 'Despesa' ?> —
                        R$ <?= number_format((float) $lancamento['valor'], 2, ',', '.') ?>
                        <a href="index.php?pagina=editar&id=<?= $lancamento['id'] ?>">[Editar]</a>
                        <a href="index.php?pagina=excluir&id=<?= $lancamento['id'] ?>"
                            onclick="return confirm('Tem certeza de que deseja excluir este lançamento?')">[Excluir]</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Valores por categoria</h2>
        <?php if (empty($porCategoria)): ?>
            <p>Nenhum valor por categoria neste mês.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($porCategoria as $item): ?>
                    <li><?= htmlspecialchars($item['categoria']) ?>
                        (<?= $item['tipo'] === 'receita' ? 'Receita' : 'Despesa' ?>): R$
                        <?= number_format((float) $item['total'], 2, ',', '.') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>

</html>