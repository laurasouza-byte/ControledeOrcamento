<?php

namespace Controller;

use Model\Lancamento;

class LancamentoController
{
    private Lancamento $model;

    public function __construct()
    {
        $this->model = new Lancamento();
    }

    public function categorias(): array
    {
        return ['Alimentação', 'Transporte', 'Moradia', 'Lazer', 'Saúde', 'Salário', 'Outros'];
    }

    private function validar(string $valor, string $tipo, string $categoria, string $data): ?string
    {
        if ($valor === '' || !is_numeric($valor) || (float) $valor <= 0) {
            return 'Informe um valor numérico maior que zero.';
        }

        if (!in_array($tipo, ['receita', 'despesa'], true)) {
            return 'Tipo inválido.';
        }

        if (!in_array($categoria, $this->categorias(), true)) {
            return 'Categoria inválida.';
        }

        if ($data === '') {
            return 'Informe a data.';
        }

        $partes = explode('-', $data);
        if (count($partes) !== 3 || !checkdate((int) $partes[1], (int) $partes[2], (int) $partes[0])) {
            return 'Data inválida.';
        }

        if ($data > date('Y-m-d')) {
            return 'A data não pode ser futura.';
        }

        return null;
    }

    public function cadastrar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?pagina=lancamento');
            exit;
        }

        $valor = trim((string) filter_input(INPUT_POST, 'valor', FILTER_SANITIZE_SPECIAL_CHARS));
        $tipo = trim((string) filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS));
        $categoria = trim((string) filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_SPECIAL_CHARS));
        $data = trim((string) filter_input(INPUT_POST, 'data', FILTER_SANITIZE_SPECIAL_CHARS));

        $erro = $this->validar($valor, $tipo, $categoria, $data);
        if ($erro !== null) {
            header('Location: index.php?pagina=lancamento&erro=' . urlencode($erro));
            exit;
        }

        if ($this->model->salvar((float) $valor, $tipo, $categoria, $data)) {
            header('Location: index.php?pagina=home&sucesso=' . urlencode('Lançamento cadastrado com sucesso.'));
        } else {
            header('Location: index.php?pagina=home&erro=' . urlencode('Erro ao cadastrar lançamento.'));
        }
        exit;
    }

    public function atualizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?pagina=home');
            exit;
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id <= 0) {
            header('Location: index.php?pagina=home&erro=' . urlencode('ID inválido.'));
            exit;
        }

        $valor = trim((string) filter_input(INPUT_POST, 'valor', FILTER_SANITIZE_SPECIAL_CHARS));
        $tipo = trim((string) filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS));
        $categoria = trim((string) filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_SPECIAL_CHARS));
        $data = trim((string) filter_input(INPUT_POST, 'data', FILTER_SANITIZE_SPECIAL_CHARS));

        $erro = $this->validar($valor, $tipo, $categoria, $data);
        if ($erro !== null) {
            header('Location: index.php?pagina=editar&id=' . $id . '&erro=' . urlencode($erro));
            exit;
        }

        if ($this->model->buscarPorId($id) === null) {
            header('Location: index.php?pagina=home&erro=' . urlencode('Lançamento não encontrado.'));
            exit;
        }

        if ($this->model->atualizar($id, (float) $valor, $tipo, $categoria, $data)) {
            header('Location: index.php?pagina=home&sucesso=' . urlencode('Lançamento atualizado com sucesso.'));
        } else {
            header('Location: index.php?pagina=home&erro=' . urlencode('Erro ao atualizar lançamento.'));
        }
        exit;
    }

    public function excluir(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id === false || $id === null || $id <= 0) {
            header('Location: index.php?pagina=home&erro=' . urlencode('ID inválido.'));
            exit;
        }

        if ($this->model->buscarPorId($id) === null) {
            header('Location: index.php?pagina=home&erro=' . urlencode('Lançamento não encontrado.'));
            exit;
        }

        if ($this->model->excluir($id)) {
            header('Location: index.php?pagina=home&sucesso=' . urlencode('Lançamento excluído com sucesso.'));
        } else {
            header('Location: index.php?pagina=home&erro=' . urlencode('Erro ao excluir lançamento.'));
        }
        exit;
    }

    public function buscarParaEdicao(int $id): ?array
    {
        $lancamento = $this->model->buscarPorId($id);

        if ($lancamento === null) {
            header('Location: index.php?pagina=home&erro=' . urlencode('Lançamento não encontrado.'));
            exit;
        }

        return $lancamento;
    }

    public function calcularSaldo(array $lancamentos): array
    {
        $totalReceitas = 0;
        $totalDespesas = 0;

        foreach ($lancamentos as $lancamento) {
            if ($lancamento['tipo'] === 'receita') {
                $totalReceitas += (float) $lancamento['valor'];
            }

            if ($lancamento['tipo'] === 'despesa') {
                $totalDespesas += (float) $lancamento['valor'];
            }
        }

        return [
            'total_receitas' => $totalReceitas,
            'total_despesas' => $totalDespesas,
            'saldo' => $totalReceitas - $totalDespesas,
        ];
    }

    public function home(): void
    {
        $mesAno = trim((string) filter_input(INPUT_GET, 'mes_ano', FILTER_SANITIZE_SPECIAL_CHARS));
        if (!preg_match('/^\d{4}-\d{2}$/', $mesAno)) {
            $mesAno = date('Y-m');
        }

        $categoria = trim((string) filter_input(INPUT_GET, 'categoria', FILTER_SANITIZE_SPECIAL_CHARS));
        if ($categoria !== '' && !in_array($categoria, $this->categorias(), true)) {
            $categoria = '';
        }

        $lancamentos = $this->model->listar($mesAno, $categoria !== '' ? $categoria : null);
        $resumo = $this->calcularSaldo($this->model->listar($mesAno));
        $porCategoria = $this->model->totaisPorCategoria($mesAno);
        $categorias = $this->categorias();

        include __DIR__ . '/../View/home.php';
    }
}
