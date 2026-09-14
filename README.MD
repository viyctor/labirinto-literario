============================================
LABIRINTO LITERÁRIO
Sistema de vitrine com login e venda (PHP)
============================================

INTEGRANTES:
- João Victor Santos
- Yasmim Santos Jardim

DESCRIÇÃO:
Loja virtual de livros de ficção. O utilizador pode:
- Ver a vitrine pública com 183 livros (lidos da BD)
- Adicionar livros ao carrinho
- Cadastrar-se em 2 etapas (dados pessoais + login/senha)
- Fazer login
- Confirmar a compra e escolher forma de pagamento
- Ver a confirmação do pedido

TECNOLOGIAS:
- PHP
- MySQL (banco de dados "labirinto literario")
- HTML
- CSS
- JavaScript

ESTRUTURA DE PASTAS:
PLL/
├── index.php                (vitrine)
├── login.php                (login)
├── cadastro-dados.php       (cadastro etapa 1)
├── cadastro-acesso.php      (cadastro etapa 2)
├── confirmar-compra.php     (confirmação)
├── sucesso.php              (compra confirmada)
├── erro.php                 (página de erro)
├── sair.php                 (logout)
├── listar-livros.php        (devolve os livros em JSON)
│
├── salvar-usuario.php       (grava dados pessoais na BD)
├── salvar-acesso.php        (grava login/senha na BD)
├── processa-entrar.php      (valida login)
├── salvar-venda.php         (grava venda na BD)
│
├── funcoes.php              (funções auxiliares)
├── banco.sql                (script para criar as tabelas)
│
├── banco/
│   ├── cons.php             (dados de conexão)
│   └── DLL.php              (função banco)
│
├── css/
├── js/                      (main.js)
└── img/                     (imagens e capas)

COMO EXECUTAR:
1. Copiar a pasta Labirinto Literario para C:\xampp\htdocs\
2. Ligar o Apache e o MySQL no XAMPP
3. Abrir o phpMyAdmin: http://localhost/phpmyadmin
4. Criar um banco de dados novo chamado labirinto-literario
5. Selecionar esse banco e importar o ficheiro banco.sql (cria as tabelas e já popula os 183 livros)
6. Ajustar banco/cons.php se necessário (utilizador e senha do MySQL)
7. Acessar: http://localhost/Labirinto%20Literario/index.php

BANCO DE DADOS:
Base: labirinto literario
Tabelas:
- usuarios      (nome, cpf, endereco, bairro, cidade, estado, cep)
- acesso        (login, senha, cpf)
- livros        (titulo, autor, preco, categoria, destaque, imagem, sinopse)
- vendas        (numero_venda, login, nome, data_hora, forma_pagamento, total)
- venda_itens   (venda_id, titulo, autor, preco, quantidade, categoria)

A senha do utilizador é guardada com password_hash() do PHP.