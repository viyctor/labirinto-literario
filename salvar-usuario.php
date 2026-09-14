<?php
include "banco/cons.php";
require_once "banco/DLL.php";
extract($_POST);
session_start();

if(isset($B1)){
    $verifica = "SELECT * FROM usuarios WHERE cpf = '$cpf'";
    $resultado = banco($server, $user, $password, $db, $verifica);
    $linha = $resultado->fetch_assoc();

    if($linha){
        $_SESSION['cadastro_erro'] = 'Este CPF já está cadastrado.';
        header('Location: cadastro-dados.php');
        exit;
    }

    $consulta = "INSERT INTO usuarios (nome, cpf, endereco, bairro, cidade, estado, cep) 
                 VALUES ('$nome', '$cpf', '$endereco', '$bairro', '$cidade', '$estado', '$cep')";
    banco($server, $user, $password, $db, $consulta);

    $_SESSION['cadastro_dados'] = [
        'cpf'  => $cpf,
        'nome' => $nome,
    ];

    header('Location: cadastro-acesso.php');
    exit;
}else{
    header('Location: cadastro-dados.php');
    exit;
}
?>