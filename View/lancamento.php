<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Novo Lançamento</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1>Novo Lançamento</h1>
    <p><a href="index.php?pagina=home">← Voltar</a></p>

    <?php if (isset($_GET['erro'])): ?>
        <p class="erro"><?= htmlspecialchars($_GET['erro']) ?></p>
    <?php endif; ?>

    <div class="card">
        <form method="POST" action="index.php?pagina=cadastrar">
            <p>
                <label>Valor (R$) *<br>
                    <input type="text" name="valor" required placeholder="0.00">
                </label>
            </p>
            <p>
                <label>Tipo *<br>
                    <select name="tipo" required>
                        <option value="receita">Receita</option>
                        <option value="despesa">Despesa</option>
                    </select>
                </label>
            </p>
            <p>
                <label>Categoria *<br>
                    <select name="categoria" required>
                        <?php foreach ($categorias as $opcao): ?>
                            <option value="<?= htmlspecialchars($opcao) ?>"><?= htmlspecialchars($opcao) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </p>
            <p>
                <label>Data *<br>
                    <input type="date" name="data" required value="<?= date('Y-m-d') ?>">
                </label>
            </p>
            <p>
                <button type="submit">Salvar</button>
                <a href="index.php?pagina=home">Cancelar</a>
            </p>
        </form>
    </div>
</body>

</html>