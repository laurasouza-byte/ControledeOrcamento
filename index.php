<?php

require_once __DIR__ . '/vendor/autoload.php';

use Controller\LancamentoController;

$controller = new LancamentoController();
$pagina = $_GET['pagina'] ?? 'home';

if ($pagina === 'cadastrar') {
    $controller->cadastrar();
}

if ($pagina === 'atualizar') {
    $controller->atualizar();
}

if ($pagina === 'excluir') {
    $controller->excluir();
}

if ($pagina === 'lancamento') {
    $categorias = $controller->categorias();
    include __DIR__ . '/View/lancamento.php';
    exit;
}

if ($pagina === 'editar') {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id === false || $id === null || $id <= 0) {
        header('Location: index.php?pagina=home&erro=' . urlencode('ID inválido.'));
        exit;
    }
    $lancamento = $controller->buscarParaEdicao($id);
    if ($lancamento !== null) {
        $categorias = $controller->categorias();
        include __DIR__ . '/View/editar.php';
        exit;
    }
}

$controller->home();