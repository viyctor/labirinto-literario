<?php
extract($_POST);
session_start();
include "banco/cons.php";
require_once "banco/DLL.php";

if(isset($B1)){
    $consulta = "SELECT a.login, a.senha, u.nome
                 FROM acesso a
                 JOIN usuarios u ON u.cpf = a.cpf
                 WHERE a.login = '$login'";
    $resultado = banco($server, $user, $password, $db, $consulta);
    $dados = $resultado->fetch_assoc();

    if($dados && password_verify($senha, $dados['senha'])){
        $_SESSION['usuario']      = $dados['login'];
        $_SESSION['usuario_nome'] = $dados['nome'];

        $destino = 'confirmar-compra.php';
        if(isset($next) && $next === 'vitrine'){
            $destino = 'index.php';
        }
        header("Location: $destino");
        exit;
    }else{
        $_SESSION['login_erro'] = 'Login ou senha incorretos.';
        header('Location: erro.php?motivo=login');
        exit;
    }
}else{
    header('Location: login.php');
    exit;
}
?>