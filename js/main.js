let cart = JSON.parse(sessionStorage.getItem('folio_cart') || '[]');
let filtroAtual = 'all';
let LIVROS = [];

function carregarLivrosDoServidor() {
  fetch('listar-livros.php')
    .then(function(r) { return r.json(); })
    .then(function(data) {
      LIVROS = data;
      renderizarLivros(LIVROS);
      atualizarContadorCarrinho();
      if (USUARIO_LOGADO) executarIntencaoPendente();
    })
    .catch(function() {
      document.getElementById('productsGrid').innerHTML =
        '<div class="empty-state">Erro ao carregar livros do servidor.</div>';
    });
}

const TEMA_PADRAO = { bg: '#020d1f', accent: '#00c8ff' };
const TEMAS_POR_CATEGORIA = {};

function temaDaCategoria(categoria) {
  if (TEMAS_POR_CATEGORIA[categoria]) {
    return TEMAS_POR_CATEGORIA[categoria];
  }
  return TEMA_PADRAO;
}

function coresDeTema(tema) {
  const fundoEscuro = tema.bg.startsWith('#0') || tema.bg === '#1c1917';
  let texto = '#1a1a1a';
  let subtexto = 'rgba(0,0,0,0.5)';
  if (fundoEscuro) {
    texto = '#fff';
    subtexto = 'rgba(255,255,255,0.6)';
  }
  return { texto: texto, subtexto: subtexto };
}

function renderizarLivros(lista) {
  const grid       = document.getElementById('productsGrid');
  const contador   = document.getElementById('catalogCount');

  let plural = '';
  if (lista.length !== 1) {
    plural = 's';
  }
  contador.textContent = `${lista.length} livro${plural} encontrado${plural}`;

  if (lista.length === 0) {
    grid.innerHTML = `<div class="empty-state">Nenhum livro encontrado.</div>`;
    return;
  }

  let html = '';
  for (let i = 0; i < lista.length; i++) {
    html += montarCardLivro(lista[i]);
  }
  grid.innerHTML = html;
}

function montarCardLivro(livro) {
  const tema  = temaDaCategoria(livro.categoria);
  const cores = coresDeTema(tema);
  let capa = `<div class="cover-title">${livro.titulo}</div>`;
  if (livro.imagem) {
    capa = `<img src="img/capas/${livro.imagem}" alt="${livro.titulo}" style="width:100%;height:100%;object-fit:cover;border-radius:4px;">`;
  }

  let classeDestaque = '';
  let badgeDestaque = '';
  if (livro.destaque) {
    classeDestaque = 'destaque';
    badgeDestaque = '<div class="badge-destaque"><img src="img/icons/12.png" alt="Destaque" class="icon-xs"> Destaque</div>';
  }

  return `
    <div class="book-card ${classeDestaque}"
         style="--card-bg:${tema.bg};--card-accent:${tema.accent};--card-text:${cores.texto};--card-sub:${cores.subtexto}"
         data-id="${livro.id}"
         onclick="abrirModalLivro(${livro.id})">
      ${badgeDestaque}
      <div class="book-cover" style="background:linear-gradient(135deg,${tema.accent}22 0%,${tema.accent}55 100%)">
        ${capa}
      </div>
      <div class="book-info">
        <span class="book-cat" style="color:${tema.accent}">${livro.categoria}</span>
        <h3 class="book-titulo">${livro.titulo}</h3>
        <p class="book-autor">${livro.autor}</p>
        <div class="book-footer">
          <span class="book-preco">${livro.preco}</span>
          <div class="book-actions">
            <button class="btn-carrinho-card"
              onclick="event.stopPropagation();handleAdicionarAoCarrinho(${livro.id})">
              + Carrinho
            </button>
            <button class="btn-comprar"
              onclick="event.stopPropagation();handleComprarAgora(${livro.id})">
              Comprar
            </button>
          </div>
        </div>
      </div>
    </div>`;
}

function abrirModalLivro(id) {
  let livro = null;
  for (let i = 0; i < LIVROS.length; i++) {
    if (LIVROS[i].id === id) {
      livro = LIVROS[i];
      break;
    }
  }
  if (!livro) return;

  let extras = livro;
  const tema   = temaDaCategoria(livro.categoria);
  const cores  = coresDeTema(tema);

  aplicarTemaNoModal(tema, cores);
  preencherCapaNoModal(livro, tema);
  preencherInfoNoModal(livro, extras, tema);
  registrarBotoesDoModal(livro, tema);

  document.getElementById('bookModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function aplicarTemaNoModal(tema, cores) {
  const acoes = document.querySelector('.book-modal-actions');
  acoes.innerHTML = `
    <button class="btn-modal-comprar" id="modalBtnComprar">Comprar Agora</button>
    <button class="btn-modal-carrinho" id="modalBtnCarrinho">+ Carrinho</button>`;

  document.querySelector('.book-modal-specs-grid').innerHTML = `
    <div class="spec-item"><span class="spec-label">Editora</span><span class="spec-value" id="specEditora"></span></div>
    <div class="spec-item"><span class="spec-label">Autor</span><span class="spec-value" id="specAutor"></span></div>
    <div class="spec-item"><span class="spec-label">Publicação</span><span class="spec-value" id="specPublicacao"></span></div>
    <div class="spec-item"><span class="spec-label">Edição</span><span class="spec-value" id="specEdicao"></span></div>
    <div class="spec-item"><span class="spec-label">Idioma</span><span class="spec-value" id="specIdioma"></span></div>
    <div class="spec-item"><span class="spec-label">Número de Páginas</span><span class="spec-value" id="specPaginas"></span></div>
    <div class="spec-item"><span class="spec-label">Peso</span><span class="spec-value" id="specPeso"></span></div>
    <div class="spec-item"><span class="spec-label">Idade</span><span class="spec-value" id="specIdade"></span></div>`;
}

function preencherCapaNoModal(livro, tema) {
  const capa = document.getElementById('modalCover');
  capa.style.background = `linear-gradient(135deg,${tema.accent}33 0%,${tema.accent}88 100%)`;

  if (livro.imagem) {
    capa.innerHTML = `<img src="img/capas/${livro.imagem}" alt="${livro.titulo}">`;
  } else {
    capa.innerHTML = `
      <div class="cover-title" style="color:${tema.accent};font-family:'Playfair Display',serif;font-size:0.85rem;font-weight:700;text-align:center;line-height:1.3;padding:0 0.5rem">
        ${livro.titulo}
      </div>`;
  }
}

function preencherInfoNoModal(livro, extras, tema) {
  const catEl = document.getElementById('modalCat');
  catEl.textContent = livro.categoria;
  catEl.style.color = tema.accent;

  document.getElementById('modalTitulo').textContent  = livro.titulo;
  document.getElementById('modalAutor').textContent   = livro.autor;

  const precoEl = document.getElementById('modalPreco');
  precoEl.textContent  = livro.preco;
  precoEl.style.color  = tema.accent;

  document.getElementById('modalSinopse').textContent   = extras.sinopse    || '';
  document.getElementById('specEditora').textContent    = extras.editora    || '';
  document.getElementById('specAutor').textContent      = livro.autor       || '';
  document.getElementById('specPublicacao').textContent = extras.publicacao || '';
  document.getElementById('specEdicao').textContent     = extras.edicao     || '';
  document.getElementById('specIdioma').textContent     = extras.idioma     || '';
  document.getElementById('specPaginas').textContent    = extras.paginas    || '';
  document.getElementById('specPeso').textContent       = extras.peso       || '';
  document.getElementById('specIdade').textContent      = extras.idade      || '';
}

function registrarBotoesDoModal(livro, tema) {
  const btnComprar   = document.getElementById('modalBtnComprar');
  const btnCarrinho  = document.getElementById('modalBtnCarrinho');

  btnComprar.style.background  = tema.accent;
  btnComprar.onclick = function() {
    fecharModalLivro();
    handleComprarAgora(livro.id);
  };

  btnCarrinho.style.borderColor = tema.accent;
  btnCarrinho.style.setProperty('--btn-carrinho-color', tema.accent);
  btnCarrinho.onclick = function() {
    fecharModalLivro();
    handleAdicionarAoCarrinho(livro.id);
  };
}

function abrirModalCardoAzul() {
  const livroCardoAzul = {
    id:        null,
    titulo:    'Um Cardo-Azul',
    autor:     'Helaine Araújo',
    categoria: 'Romance Fantasia',
    preco:     'R$ 120,00',
    imagem:    '../cardo-azul-fisico.jpg',
  };
  const extras = {
  sinopse: 'Nascida em um mundo marcado pela existência de magos/as e usuários/as de afinidades, Hovy é uma usuária de gelo com um passado sombrio, que busca uma maga poderosa - a lenda conhecida como a Bruxa. Em sua jornada, ela conhece Aaron, um usuário de fogo que se tornou o mais jovem cavaleiro à serviço direto do Imperador, sendo conhecido como o prodígio do Império de Mattiya. Depois de um primeiro encontro nada amistoso, eles se reencontram meses depois e, por coincidência do destino (talvez), passam a viajar juntos, compartilhando o mesmo objetivo. O encontro entre a solidão e a liberdade de Hovy com o espírito cativo de Aaron, prisioneiro das próprias responsabilidades, explode e os transforma. O convívio diário poderá libertá-los das próprias amarras?',
  edicao:    '1ª edição',
  idioma:    'Português',
  paginas:   '592 páginas',
  editora:   'Dialética',
  publicacao: '25 agosto de 2026',
  peso:      '845 g',
  idade:     '???',
  };
  const tema = { bg: '#020d1f', accent: '#00c8ff' };

  aplicarTemaNoModal(tema, coresDeTema(tema));
  preencherCapaNoModal(livroCardoAzul, tema);
  preencherInfoNoModal(livroCardoAzul, extras, tema);

  document.querySelector('.book-modal-actions').innerHTML = `
    <button class="btn-hero-primary" id="modalBtnReservar"
      style="background:${tema.accent};color:#020d1f;border:none;padding:0.75rem 1.5rem;border-radius:999px;font-weight:700;cursor:pointer;font-size:1rem;">
      Reservar - R$ 120,00
    </button>`;
  document.getElementById('modalBtnReservar').onclick = function() {
    fecharModalLivro();
    handleComprarAgoraComDados('Um Cardo-Azul (Físico)', 'Helaine Araújo', 'R$ 120,00', 'Romance Fantasia');
  };

  document.getElementById('bookModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function fecharModalLivro() {
  document.getElementById('bookModal').classList.remove('open');
  document.body.style.overflow = '';
}

document.getElementById('bookModal').addEventListener('click', function(e) {
  if (e.target === this) fecharModalLivro();
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    fecharModalLivro();
    fecharCarrinho();
  }
});

function salvarIntencao(tipo, titulo, autor, preco, categoria) {
  sessionStorage.setItem('intencao', JSON.stringify({ tipo, titulo, autor, preco, categoria }));
}

function handleComprarAgora(id) {
  let livro = null;
  for (let i = 0; i < LIVROS.length; i++) {
    if (LIVROS[i].id === id) {
      livro = LIVROS[i];
      break;
    }
  }
  if (livro) {
    handleComprarAgoraComDados(livro.titulo, livro.autor, livro.preco, livro.categoria);
  }
}

function handleComprarAgoraComDados(titulo, autor, preco, categoria) {
  if (USUARIO_LOGADO) {
    adicionarAoCarrinhoSilencioso(titulo, autor, preco, categoria);
    window.location.href = 'confirmar-compra.php';
  } else {
    salvarIntencao('comprar', titulo, autor, preco, categoria);
    window.location.href = 'login.php?next=vitrine';
  }
}

function handleAdicionarAoCarrinho(id) {
  let livro = null;
  for (let i = 0; i < LIVROS.length; i++) {
    if (LIVROS[i].id === id) {
      livro = LIVROS[i];
      break;
    }
  }
  if (!livro) return;

  if (USUARIO_LOGADO) {
    adicionarAoCarrinho(livro.titulo, livro.autor, livro.preco, livro.categoria);
  } else {
    salvarIntencao('carrinho', livro.titulo, livro.autor, livro.preco, livro.categoria);
    window.location.href = 'login.php?next=vitrine';
  }
}

function executarIntencaoPendente() {
  const raw = sessionStorage.getItem('intencao');
  if (!raw) return;

  let intencao;
  try { intencao = JSON.parse(raw); } catch (e) { return; }
  sessionStorage.removeItem('intencao');

  if (!intencao || !intencao.titulo) return;

  let jaNoCarrinho = null;
  for (let i = 0; i < cart.length; i++) {
    if (cart[i].titulo === intencao.titulo) {
      jaNoCarrinho = cart[i];
      break;
    }
  }
  if (!jaNoCarrinho) {
    cart.push({ titulo: intencao.titulo, autor: intencao.autor, preco: intencao.preco, categoria: intencao.categoria, qty: 1 });
    salvarCarrinho();
    atualizarContadorCarrinho();
  }

  if (intencao.tipo === 'comprar') {
    mostrarToast(`"${intencao.titulo}" adicionado ao carrinho!`);
  } else {
    mostrarToast(`"${intencao.titulo}" adicionado ao carrinho!`);
  }
}

function filtrarCategoria(categoria) {
  filtroAtual = categoria;

  const botoes = document.querySelectorAll('.cat-pill');
  for (let i = 0; i < botoes.length; i++) {
    botoes[i].classList.remove('active');
  }
  event.target.classList.add('active');

  let lista = [];
  if (categoria === 'all') {
    lista = LIVROS;
  } else {
    for (let i = 0; i < LIVROS.length; i++) {
      if (LIVROS[i].categoria === categoria) {
        lista.push(LIVROS[i]);
      }
    }
  }
  const titulo = document.getElementById('catalogTitle');
  if (categoria === 'all') {
    titulo.textContent = 'Todo o Catálogo';
  } else {
    titulo.textContent = categoria;
  }
  renderizarLivros(lista);
}

function buscarLivros() {
  const termo = document.getElementById('searchInput').value.toLowerCase().trim();
  if (!termo) { renderizarLivros(LIVROS); return; }

  const resultado = [];
  for (let i = 0; i < LIVROS.length; i++) {
    const livro = LIVROS[i];
    if (livro.titulo.toLowerCase().includes(termo) ||
        livro.autor.toLowerCase().includes(termo) ||
        livro.categoria.toLowerCase().includes(termo)) {
      resultado.push(livro);
    }
  }
  document.getElementById('catalogTitle').textContent = `Resultados para "${termo}"`;
  renderizarLivros(resultado);
}

document.getElementById('searchInput').addEventListener('keydown', function(e) {
  if (e.key === 'Enter') buscarLivros();
});

function precoParaFloat(preco) {
  return parseFloat(preco.replace('R$ ', '').replace(',', '.'));
}

function adicionarAoCarrinhoSilencioso(titulo, autor, preco, categoria) {
  let itemExistente = null;
  for (let i = 0; i < cart.length; i++) {
    if (cart[i].titulo === titulo) {
      itemExistente = cart[i];
      break;
    }
  }
  if (itemExistente) {
    itemExistente.qty++;
  } else {
    cart.push({ titulo, autor, preco, categoria, qty: 1 });
  }
  salvarCarrinho();
  atualizarContadorCarrinho();
}

function adicionarAoCarrinho(titulo, autor, preco, categoria) {
  adicionarAoCarrinhoSilencioso(titulo, autor, preco, categoria);
  mostrarToast(`"${titulo}" adicionado ao carrinho!`);
}

function salvarCarrinho() {
  sessionStorage.setItem('folio_cart', JSON.stringify(cart));
}

function atualizarContadorCarrinho() {
  let total = 0;
  for (let i = 0; i < cart.length; i++) {
    total = total + cart[i].qty;
  }
  document.getElementById('cartCount').textContent = total;
}

function abrirCarrinho() {
  document.getElementById('cartModal').classList.add('open');
  renderizarCarrinho();
}

function fecharCarrinho() {
  document.getElementById('cartModal').classList.remove('open');
}

document.getElementById('cartBtn').addEventListener('click', abrirCarrinho);

function renderizarCarrinho() {
  const container = document.getElementById('cartItems');

  if (cart.length === 0) {
    container.innerHTML = '<p class="cart-empty">Seu carrinho está vazio.</p>';
    document.getElementById('cartTotal').textContent = 'R$ 0,00';
    return;
  }

  let total = 0;
  let html = '';

  for (let indice = 0; indice < cart.length; indice++) {
    const item = cart[indice];
    const valor = precoParaFloat(item.preco);
    total += valor * item.qty;

    html += `
      <div class="cart-item">
        <div class="cart-item-info">
          <strong>${item.titulo}</strong>
          <small>${item.autor}</small>
        </div>
        <div class="cart-item-controls">
          <button onclick="alterarQuantidade(${indice}, -1)"><img src="img/icons/15.png" alt="Menos" class="icon-xs"></button>
          <span>${item.qty}</span>
          <button onclick="alterarQuantidade(${indice}, 1)"><img src="img/icons/14.png" alt="Mais" class="icon-xs"></button>
        </div>
        <div class="cart-item-price">${item.preco}</div>
        <button class="cart-remove" onclick="removerItem(${indice})">
          <img src="img/icons/13.png" alt="Remover" class="icon-xs">
        </button>
      </div>`;
  }

  container.innerHTML = html;

  document.getElementById('cartTotal').textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
}

function alterarQuantidade(indice, delta) {
  cart[indice].qty += delta;
  if (cart[indice].qty <= 0) cart.splice(indice, 1);
  salvarCarrinho();
  atualizarContadorCarrinho();
  renderizarCarrinho();
}

function removerItem(indice) {
  cart.splice(indice, 1);
  salvarCarrinho();
  atualizarContadorCarrinho();
  renderizarCarrinho();
}

function finalizarCompra() {
  if (cart.length === 0) { mostrarToast('Adicione livros antes de finalizar!'); return; }
  salvarCarrinho();
  if (USUARIO_LOGADO) {
    window.location.href = 'confirmar-compra.php';
  } else {
    window.location.href = 'login.php?next=vitrine';
  }
}

function rolarPara(id) {
  document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
}

function mostrarToast(mensagem) {
  const toast = document.createElement('div');
  toast.className  = 'toast';
  toast.textContent = mensagem;
  document.body.appendChild(toast);
  setTimeout(function() {
    toast.classList.add('show');
  }, 10);
  setTimeout(function() {
    toast.classList.remove('show');
    setTimeout(function() {
      toast.remove();
    }, 300);
  }, 2500);
}

carregarLivrosDoServidor();

/* Viyctor & Yasmim */