<?php
session_start();
$motivo = 'generico';
if (isset($_GET['motivo'])) {
    $motivo = $_GET['motivo'];
}
$mensagens = [
    'login'    => 'Login ou senha incorretos. Verifique seus dados e tente novamente.',
    'acesso'   => 'Você precisa estar logado para acessar esta página.',
    'generico' => 'Ocorreu um erro inesperado. Tente novamente.',
];
$mensagem = $mensagens['generico'];
if (isset($mensagens[$motivo])) {
    $mensagem = $mensagens[$motivo];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Erro - Labirinto Literário</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/geral.css">
    <link rel="stylesheet" href="css/autenticacao.css">
</head>
<body class="auth-page">
  <div class="auth-box" style="text-align:center">
    <div class="auth-logo">
      <a href="index.php" style="text-decoration:none">
        <img src="img/icons/1.png" alt="Labirinto Literário" class="auth-logo-img">
      </a>
    </div>
    <div style="font-size:3.5rem; margin: 0.5rem 0;">
      <img src="img/icons/27.png" alt="Erro" style="width:64px;height:64px;object-fit:contain;">
    </div>
    <h2 style="color:#c0392b; margin-bottom:0.75rem;">Acesso negado</h2>
    <div class="alert alert-error" style="text-align:left; margin-bottom:1.5rem;">
      <?php echo htmlspecialchars($mensagem) ?>
    </div>
    <?php if ($motivo === 'login'): ?>
      <a href="login.php" class="btn-submit" style="display:block; text-decoration:none; margin-bottom:0.75rem;">
        Tentar novamente
      </a>
      <div class="auth-links">
        Não tem conta? <a href="cadastro-dados.php">Cadastre-se</a>
      </div>
    <?php else: ?>
      <a href="index.php" class="btn-submit" style="display:block; text-decoration:none; margin-bottom:0.75rem;">
        Voltar à loja
      </a>
    <?php endif; ?>
    <div class="auth-links" style="margin-top:0.5rem;">
      <a href="index.php">← Página inicial</a>
    </div>
  </div>
</body>
</html>