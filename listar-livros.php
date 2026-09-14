<?php
include "banco/cons.php";
require_once "banco/DLL.php";

header('Content-Type: application/json; charset=utf-8');

$consulta = "SELECT * FROM livros ORDER BY id";
$resultado = banco($server, $user, $password, $db, $consulta);

$livros = array();
while($linha = $resultado->fetch_assoc()){
    $linha['destaque'] = (int)$linha['destaque'];
    $linha['id']       = (int)$linha['id'];
    $livros[] = $linha;
}

echo json_encode($livros, JSON_UNESCAPED_UNICODE);
?>