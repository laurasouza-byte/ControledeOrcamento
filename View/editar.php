<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Lançamento</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1>Editar Lançamento</h1>
    <p><a href="index.php?pagina=home">← Voltar</a></p>

    <?php if (isset($_GET['erro'])): ?>
        <p class="erro"><?= htmlspecialchars($_GET['erro']) ?></p>
    <?php endif; ?>

    <div class="card">
        <form method="POST" action="index.php?pagina=atualizar">
            <input type="hidden" name="id" value="<?= htmlspecialchars($lancamento['id']) ?>">
            <p>
                <label>Valor (R$) *<br>
                    <input type="text" name="valor" required value="<?= htmlspecialchars($lancamento['valor']) ?>">
                </label>
            </p>
            <p>
                <label>Tipo *<br>
                    <select name="tipo" required>
                        <option value="receita" <?= $lancamento['tipo'] === 'receita' ? 'selected' : '' ?>>Receita</option>
                        <option value="despesa" <?= $lancamento['tipo'] === 'despesa' ? 'selected' : '' ?>>Despesa</option>
                    </select>
                </label>
            </p>
            <p>
                <label>Categoria *<br>
                    <select name="categoria" required>
                        <?php foreach ($categorias as $opcao): ?>
                            <option value="<?= htmlspecialchars($opcao) ?>" <?= $lancamento['categoria'] === $opcao ? 'selected' : '' ?>><?= htmlspecialchars($opcao) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </p>
            <p>
                <label>Data *<br>
                    <input type="date" name="data" required value="<?= htmlspecialchars($lancamento['data']) ?>">
                </label>
            </p>
            <p>
                <button type="submit">Atualizar</button>
                <a href="index.php?pagina=home">Cancelar</a>
            </p>
        </form>
    </div>
</body>

</html>