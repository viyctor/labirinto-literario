<?php
session_start();
if (!isset($_SESSION['usuario'])) { header('Location: login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Confirmar Compra - Labirinto Literário</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/geral.css">
  <link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/pagamento.css">
  <link rel="stylesheet" href="css/rodape.css">
</head>
<body class="auth-page" style="background:#030f22;min-height:100vh;justify-content:flex-start;padding-top:2rem">
  <nav class="navbar" style="position:fixed;top:0;left:0;right:0">
    <div class="nav-inner">
      <a href="index.php" class="logo"><img src="img/icons/1.png" alt="Logo" class="logo-img"><span class="logo-text">Labirinto Literário</span></a>
      <div style="flex:1"></div>
      <div class="nav-links">
        <span class="nav-user">Olá, <?php if (isset($_SESSION['usuario_nome'])) { echo htmlspecialchars($_SESSION['usuario_nome']); } else { echo htmlspecialchars($_SESSION['usuario']); } ?></span>
        <a href="sair.php" class="btn-nav"><img src="img/icons/6.png" alt="Sair" class="icon-sm"> Sair</a>
      </div>
    </div>
  </nav>
  <div style="max-width:680px;width:100%;margin:90px auto 3rem;padding:0 1.5rem">
    <div class="confirm-card">
      <h3><img src="img/icons/2.png" alt="Carrinho" class="icon-sm"> Resumo do Pedido</h3>
      <div id="confirmItems" class="confirm-items">
        <p style="color:#5a9abf;font-size:0.9rem">Carregando itens...</p>
      </div>
      <div class="confirm-total" id="confirmTotal" style="display:none">
        <span>Total</span>
        <span id="totalVal">R$ 0,00</span>
      </div>
    </div>
    <div class="confirm-card" style="margin-top:1rem">
      <h3><img src="img/icons/22.png" alt="Usuário" class="icon-sm"> Dados do Comprador</h3>
      <p style="font-size:0.95rem;color:#9dd4f0">
        <strong><?php if (isset($_SESSION['usuario_nome'])) { echo htmlspecialchars($_SESSION['usuario_nome']); } else { echo htmlspecialchars($_SESSION['usuario']); } ?></strong><br>
        Usuário: <?php echo htmlspecialchars($_SESSION['usuario']) ?>
      </p>
    </div>

    <form method="POST" action="salvar-venda.php" id="formCompra">
      <input type="hidden" name="cart" id="cartHidden" value="">
      <input type="hidden" name="forma_pagamento" id="pagHidden" value="Cartão de Crédito">

      <div class="confirm-card" style="margin-top:1rem">
        <h3><img src="img/icons/17.png" alt="Pagamento" class="icon-sm"> Forma de Pagamento</h3>
        <div style="display:flex;flex-direction:column;gap:0.5rem" id="pagamento">
          <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;font-size:0.95rem">
            <input type="radio" name="pag" value="Cartão de Crédito" checked> <img src="img/icons/17.png" alt="Cartão" class="icon-xs"> Cartão de Crédito
          </label>
          <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;font-size:0.95rem">
            <input type="radio" name="pag" value="Pix"> <img src="img/icons/18.png" alt="Pix" class="icon-xs"> Pix
          </label>
          <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;font-size:0.95rem">
            <input type="radio" name="pag" value="Boleto"> <img src="img/icons/19.png" alt="Boleto" class="icon-xs"> Boleto Bancário
          </label>
        </div>
      </div>

      <button type="submit" name="B1" value="Confirmar" class="btn-confirmar-compra">
        <span class="btn-confirmar-icon">
          <img src="img/icons/20.png" alt="Confirmar" class="icon-sm">
        </span>
        <span class="btn-confirmar-texto">Confirmar Compra</span>
        <span class="btn-confirmar-arrow">→</span>
      </button>
    </form>

    <div class="auth-links" style="margin-top:1rem"><a href="index.php">← Continuar comprando</a></div>
  </div>
  <script>
    const cart = JSON.parse(sessionStorage.getItem('folio_cart') || '[]');
    const itemsDiv = document.getElementById('confirmItems');
    const totalDiv = document.getElementById('confirmTotal');
    const totalVal = document.getElementById('totalVal');

    if (cart.length === 0) {
      itemsDiv.innerHTML = '<p style="color:#5a9abf">Nenhum item no carrinho. <a href="index.php">Ver loja</a></p>';
    } else {
      let total = 0;
      let itens = '';
      for (let i = 0; i < cart.length; i++) {
        const item = cart[i];
        const val = parseFloat(item.preco.replace('R$ ','').replace(',','.'));
        total += val * item.qty;
        itens += '<div class="confirm-item"><span>' + item.titulo + ' <small style="color:#5a9abf">(' + item.autor + ')</small> × ' + item.qty + '</span><strong>R$ ' + (val * item.qty).toFixed(2).replace('.',',') + '</strong></div>';
      }
      itemsDiv.innerHTML = itens;
      totalVal.textContent = 'R$ ' + total.toFixed(2).replace('.',',');
      totalDiv.style.display = 'flex';
    }

    document.getElementById('formCompra').addEventListener('submit', function(e) {
      if (cart.length === 0) {
        e.preventDefault();
        alert('Carrinho vazio!');
        return;
      }
      const pag = document.querySelector('input[name="pag"]:checked').value;
      document.getElementById('cartHidden').value = JSON.stringify(cart);
      document.getElementById('pagHidden').value = pag;
      sessionStorage.removeItem('folio_cart');
    });
  </script>
</body>
</html>