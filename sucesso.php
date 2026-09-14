<?php
session_start();
if (!isset($_SESSION['usuario'])) { header('Location: index.php'); exit; }
$numero = '---';
if (isset($_GET['num'])) {
    $numero = htmlspecialchars($_GET['num']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Compra Confirmada - Labirinto Literário</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/geral.css">
    <link rel="stylesheet" href="css/autenticacao.css">
  <style>
    .success-wrap { text-align:center; padding: 4rem 2rem; }
    .success-icon { font-size: 5rem; animation: pop 0.5s ease-out both; }
    @keyframes pop { 0%{transform:scale(0)} 80%{transform:scale(1.15)} 100%{transform:scale(1)} }
    .success-wrap h1 { font-family:'Playfair Display',serif; font-size:2rem; margin:1rem 0 0.5rem; color:#00c8ff; }
    .success-wrap p { color:#5a9abf; font-size:1rem; line-height:1.6; }
    .order-num { background:#00c8ff; color:#7de8ff; padding:0.5rem 1.5rem; border-radius:100px; display:inline-block; margin:1rem 0; font-weight:700; font-size:1.1rem; letter-spacing:0.05em; }
  </style>
</head>
<body class="auth-page" style="background:#030f22">
  <div class="auth-box" style="max-width:480px;text-align:center">
    <div class="success-wrap">
      <div class="success-icon"><img src="img/icons/21.png" alt="Sucesso" class="icon-xl"></div>
      <h1>Compra Confirmada!</h1>
      <p>Obrigado pela sua compra na <strong>Labirinto Literário</strong>, <?php if (isset($_SESSION['usuario_nome'])) { echo htmlspecialchars($_SESSION['usuario_nome']); } else { echo htmlspecialchars($_SESSION['usuario']); } ?>!</p>
      <div class="order-num">Pedido <?php echo $numero ?></div>
      <p style="margin-top:0.5rem">Você receberá um e-mail com os detalhes do pedido em breve.</p>
      <a href="index.php" class="btn-submit" style="display:inline-block;margin-top:1.5rem;text-decoration:none;padding:0.9rem 2rem;border-radius:100px">
        Continuar Comprando
      </a>
    </div>
  </div>
</body>
</html>