<?php
include "banco/cons.php";
require_once "banco/DLL.php";
extract($_POST);
session_start();

if(!isset($_SESSION['cadastro_dados'])){
    header('Location: cadastro-dados.php');
    exit;
}

if(isset($B1)){
    $cpf = $_SESSION['cadastro_dados']['cpf'];
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $verifica = "SELECT * FROM acesso WHERE login = '$login'";
    $resultado = banco($server, $user, $password, $db, $verifica);
    $linha = $resultado->fetch_assoc();

    if($linha){
        $_SESSION['login_erro'] = 'Este login já está em uso. Escolha outro.';
        header('Location: cadastro-acesso.php');
        exit;
    }

    $consulta = "INSERT INTO acesso (login, senha, cpf) 
                 VALUES ('$login', '$senha_hash', '$cpf')";
    banco($server, $user, $password, $db, $consulta);

    unset($_SESSION['cadastro_dados']);

    header('Location: login.php');
    exit;
}else{
    header('Location: cadastro-acesso.php');
    exit;
}
?>