<?php
function precoParaFloat($preco) {
    $limpo = str_replace(['R$ ', '.', ','], ['', '', '.'], $preco);
    return (float) $limpo;
}
function usuarioAutenticado() {
    return isset($_SESSION['usuario']);
}
function garantirDiretorio($caminho) {
    if (!is_dir($caminho)) {
        mkdir($caminho, 0755, true);
    }
}