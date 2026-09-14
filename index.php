<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Labirinto Literário</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/geral.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/hero.css">
    <link rel="stylesheet" href="css/catalogo.css">
    <link rel="stylesheet" href="css/modals.css">
    <link rel="stylesheet" href="css/rodape.css">
</head>
<body>
<nav class="navbar">
  <div class="nav-inner">
    <a href="index.php" class="logo">
      <img src="img/icons/1.png" alt="Logo" class="logo-img">
    </a>
    <div class="nav-search">
      <input type="text" id="searchInput" placeholder="Buscar livro, autor ou categoria..." />
      <button onclick="buscarLivros()"><img src="img/icons/3.png" alt="Buscar" class="icon-sm"></button>
    </div>
    <div class="nav-links">
      <?php if(isset($_SESSION['usuario'])): ?>
        <span class="nav-user">Olá, <?php echo htmlspecialchars($_SESSION['usuario']) ?></span>
        <a href="sair.php" class="btn-nav"><img src="img/icons/6.png" alt="Sair" class="icon-sm"> Sair</a>
      <?php else: ?>
        <a href="login.php" class="btn-nav"><img src="img/icons/4.png" alt="Entrar" class="icon-sm"> Entrar</a>
        <a href="cadastro-dados.php" class="btn-nav btn-primary"><img src="img/icons/5.png" alt="Cadastrar" class="icon-sm"> Cadastrar</a>
      <?php endif; ?>
      <a href="#" class="cart-btn" id="cartBtn"><img src="img/icons/2.png" alt="Carrinho" class="icon-sm"> <span id="cartCount">0</span></a>
    </div>
  </div>
</nav>
<section class="hero">
  <div class="hero-bg"></div>
  <div class="stars-layer" id="starsLayer"></div>
  <div class="hero-content">
    <div class="hero-badge"><img src="img/icons/7.png" alt="Pré-venda" class="icon-sm"> PRÉ-VENDA DISPONÍVEL</div>
    <h1 class="hero-title">
      Uma duologia está prestes a começar.<br>
      <em>Um Cardo-Azul</em>
    </h1>
    <p class="hero-subtitle">Pré-venda disponível agora - primeiro romance de Helaine Araújo. Reserve o seu e receba no dia do lançamento.</p>
    <div class="hero-promo-badge">
      <img src="img/icons/7.png" alt="Promo" class="icon-xs"> Compre o Físico na pré-venda e ganhe o Kindle <strong>de graça!</strong>
    </div>
    <div class="hero-buy-options">
      <div class="buy-option">
        <button class="btn-hero-primary" onclick="handleComprarAgoraComDados('Um Cardo-Azul (Físico)','Helaine Araújo','R$ 120,00','Romance Fantasia')">
          Reservar - R$ 120,00
        </button>
      </div>
    </div>
    <div class="hero-actions" style="margin-top:1rem">
      <button class="btn-hero-secondary" onclick="rolarPara('catalogo')">Ver Catálogo</button>
    </div>
    <div class="hero-meta">
      <span><img src="img/icons/10.png" alt="Lançamento" class="icon-xs"> Lançamento em breve</span>
      <span><img src="img/icons/8.png" alt="Entrega" class="icon-xs"> Entrega no dia do lançamento</span>
      <span><img src="img/icons/9.png" alt="Seguro" class="icon-xs"> Compra segura</span>
    </div>
  </div>
  <div class="hero-visual">
    <div class="hero-books-display">
      <div class="hero-book-item hero-book-main" onclick="abrirModalCardoAzul()" style="cursor:pointer;">
        <img src="img/cardo-azul-fisico.jpg" alt="Um Cardo-Azul - Capa Física">
      </div>
      <div class="hero-book-plus">
        <img src="img/icons/14.png" alt="+" class="icon-plus-promo">
      </div>
      <div class="hero-book-item hero-book-kindle">
        <img src="img/cardo-azul-kindle.png" alt="Um Cardo-Azul - Kindle">
      </div>
    </div>
  </div>
</section>
<section class="categories-bar" id="catalogo">
  <div class="container">
    <h2 class="section-title">Categorias</h2>
    <div class="cat-pills">
      <button class="cat-pill active" onclick="filtrarCategoria('all')">Todos</button>
      <button class="cat-pill" onclick="filtrarCategoria('Romance')">Romance</button>
      <button class="cat-pill" onclick="filtrarCategoria('Romance Fantasia')">Romance Fantasia</button>
      <button class="cat-pill" onclick="filtrarCategoria('Contos')">Contos</button>
      <button class="cat-pill" onclick="filtrarCategoria('Ficção Cristã')">Ficção Cristã</button>
      <button class="cat-pill" onclick="filtrarCategoria('Ficção Científica')">Ficção Científica</button>
      <button class="cat-pill" onclick="filtrarCategoria('Suspense')">Suspense</button>
      <button class="cat-pill" onclick="filtrarCategoria('Fantasia Medieval')">Fantasia Medieval</button>
      <button class="cat-pill" onclick="filtrarCategoria('Fantasia Urbana')">Fantasia Urbana</button>
      <button class="cat-pill" onclick="filtrarCategoria('Clássicos')">Clássicos</button>
      <button class="cat-pill" onclick="filtrarCategoria('Horror')">Horror</button>
      <button class="cat-pill" onclick="filtrarCategoria('Distopia')">Distopia</button>
      <button class="cat-pill" onclick="filtrarCategoria('Novela')">Novela</button>
    </div>
  </div>
</section>
<section class="products-section">
  <div class="container">
    <div class="products-header">
      <h2 id="catalogTitle">Todo o Catálogo</h2>
      <p id="catalogCount"></p>
    </div>
    <div class="products-grid" id="productsGrid"></div>
  </div>
</section>
<div class="modal-overlay" id="cartModal">
  <div class="modal-box">
    <div class="modal-header">
      <h3><img src="img/icons/2.png" alt="Carrinho" class="icon-sm"> Seu Carrinho</h3>
      <button onclick="fecharCarrinho()"><img src="img/icons/16.png" alt="Fechar" class="icon-sm"></button>
    </div>
    <div class="modal-body" id="cartItems"></div>
    <div class="modal-footer">
      <div class="cart-total">Total: <strong id="cartTotal">R$ 0,00</strong></div>
      <button class="btn-checkout" onclick="finalizarCompra()">Finalizar Compra</button>
    </div>
  </div>
</div>
<footer class="footer">
  <div class="footer-inner">
    <div class="footer-brand">
      <img src="img/icons/1.png" alt="Labirinto Literário" class="logo-footer">
      <p>A sua livraria online com as melhores histórias do mundo.</p>
    </div>
    <div class="footer-links">
      <h4>Navegação</h4>
      <a href="index.php">Início</a>
      <a href="login.php">Entrar</a>
      <a href="cadastro-dados.php">Cadastrar</a>
    </div>
    <div class="footer-links">
      <h4>Atendimento</h4>
      <a href="#">Política de trocas</a>
      <a href="#">Frete e entrega</a>
      <a href="#">Contato</a>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2026 Labirinto Literário. Todos os direitos reservados.</p>
  </div>
</footer>
<div class="book-modal-overlay" id="bookModal">
  <div class="book-modal-box" id="bookModalBox">
    <button class="book-modal-close" onclick="fecharModalLivro()"><img src="img/icons/16.png" alt="Fechar" class="icon-sm"></button>
    <div class="book-modal-top">
      <div class="book-modal-cover" id="modalCover"></div>
      <div class="book-modal-info">
        <span class="book-modal-cat" id="modalCat"></span>
        <h2 class="book-modal-titulo" id="modalTitulo"></h2>
        <p class="book-modal-autor" id="modalAutor"></p>
        <div class="book-modal-preco" id="modalPreco"></div>
        <div class="book-modal-actions">
          <button class="btn-modal-comprar" id="modalBtnComprar">Comprar Agora</button>
          <button class="btn-modal-carrinho" id="modalBtnCarrinho">+ Carrinho</button>
        </div>
      </div>
    </div>
    <div class="book-modal-body">
      <div class="book-modal-sinopse">
        <h4>Sinopse</h4>
        <p id="modalSinopse"></p>
      </div>
      <div class="book-modal-specs">
        <h4>Informações do Produto</h4>
        <div class="book-modal-specs-grid">
          <div class="spec-item"><span class="spec-label">Editora</span><span class="spec-value" id="specEditora"></span></div>
          <div class="spec-item"><span class="spec-label">Autor</span><span class="spec-value" id="specAutor"></span></div>
          <div class="spec-item"><span class="spec-label">Data de Publicação</span><span class="spec-value" id="specPublicacao"></span></div>
          <div class="spec-item"><span class="spec-label">Edição</span><span class="spec-value" id="specEdicao"></span></div>
          <div class="spec-item"><span class="spec-label">Idioma</span><span class="spec-value" id="specIdioma"></span></div>
          <div class="spec-item"><span class="spec-label">Número de Páginas</span><span class="spec-value" id="specPaginas"></span></div>
          <div class="spec-item"><span class="spec-label">Peso</span><span class="spec-value" id="specPeso"></span></div>
          <div class="spec-item"><span class="spec-label">Idade de Leitura</span><span class="spec-value" id="specIdade"></span></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
(function(){
  const layer = document.getElementById('starsLayer');
  if(!layer) return;
  for(let i=0;i<120;i++){
    const s = document.createElement('div');
    s.className = 'star';
    s.style.cssText = `left:${Math.random()*100}%;top:${Math.random()*100}%;width:${Math.random()*2+1}px;height:${Math.random()*2+1}px;animation-delay:${Math.random()*4}s;animation-duration:${Math.random()*3+2}s`;
    layer.appendChild(s);
  }
})();
</script>
<script>
  <?php $logado = 'false'; ?>
  <?php if (isset($_SESSION['usuario'])): ?>
    <?php $logado = 'true'; ?>
  <?php endif; ?>
  const USUARIO_LOGADO = <?php echo $logado ?>;
</script>
<script src="js/main.js"></script>
</body>
</html>