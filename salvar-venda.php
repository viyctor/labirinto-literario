<?php
include "banco/cons.php";
require_once "banco/DLL.php";
require_once "funcoes.php";
extract($_POST);
session_start();
date_default_timezone_set('America/Bahia');

if(!isset($_SESSION['usuario'])){
    header('Location: login.php');
    exit;
}

if(isset($B1)){
    $carrinho = json_decode($cart, true);

    if(empty($carrinho)){
        header('Location: confirmar-compra.php');
        exit;
    }

    $pagamento = 'Não informado';
    if(isset($forma_pagamento)) $pagamento = $forma_pagamento;

    $total = 0;
    foreach($carrinho as $item){
        $qtd = 1;
        if(isset($item['qty'])) $qtd = $item['qty'];
        $total += precoParaFloat($item['preco']) * $qtd;
    }

    $numero = date('YmdHis') . rand(100,999);
    $login  = $_SESSION['usuario'];
    $nome   = $login;
    if(isset($_SESSION['usuario_nome'])) $nome = $_SESSION['usuario_nome'];
    $data_hora = date('Y-m-d H:i:s');

    $consulta = "INSERT INTO vendas (numero_venda, login, nome, data_hora, forma_pagamento, total) 
                 VALUES ('$numero', '$login', '$nome', '$data_hora', '$pagamento', $total)";
    banco($server, $user, $password, $db, $consulta);

    $consulta = "SELECT id FROM vendas WHERE numero_venda = '$numero'";
    $resultado = banco($server, $user, $password, $db, $consulta);
    $linha = $resultado->fetch_assoc();
    $venda_id = $linha['id'];

    for($i = 0; $i < count($carrinho); $i++){
        $item = $carrinho[$i];

        $titulo = $item['titulo'];
        $autor  = $item['autor'];
        $preco  = $item['preco'];

        $qtd = 1;
        if(isset($item['qty'])) $qtd = $item['qty'];

        $categoria = '';
        if(isset($item['categoria'])) $categoria = $item['categoria'];

        $consulta = "INSERT INTO venda_itens (venda_id, titulo, autor, preco, quantidade, categoria) 
                     VALUES ($venda_id, '$titulo', '$autor', '$preco', $qtd, '$categoria')";
        banco($server, $user, $password, $db, $consulta);
    }

    header("Location: sucesso.php?num=$numero");
    exit;
}else{
    header('Location: confirmar-compra.php');
    exit;
}
?>