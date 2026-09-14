<?php
session_start();
$erro = '';
if (isset($_SESSION['login_erro'])) {
    $erro = $_SESSION['login_erro'];
    unset($_SESSION['login_erro']);
}
if (isset($_SESSION['usuario'])) {
    header('Location: confirmar-compra.php');
    exit;
}
$next = 'confirmar';
if (isset($_GET['next'])) {
    $next = htmlspecialchars($_GET['next']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Entrar - Labirinto Literário</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/geral.css">
    <link rel="stylesheet" href="css/autenticacao.css">
</head>
<body class="auth-page">
  <div class="auth-box">
    <div class="auth-logo">
      <a href="index.php" style="text-decoration:none"><img src="img/icons/1.png" alt="Labirinto Literário" class="auth-logo-img"></a>
      <p>Acesse sua conta para continuar</p>
    </div>
    <h2>Entrar</h2>
    <?php if($erro): ?>
      <div class="alert alert-error"><?php echo htmlspecialchars($erro) ?></div>
    <?php endif; ?>
    <form method="POST" action="processa-entrar.php">
      <!-- repassa a intenção para o processador de login -->
      <input type="hidden" name="next" value="<?php echo $next ?>">
      <div class="form-group">
        <label><img src="img/icons/22.png" alt="Usuário" class="icon-xs"> Login</label>
        <input type="text" name="login" required placeholder="Seu usuário" value="<?php if (isset($_POST['login'])) { echo htmlspecialchars($_POST['login']); } ?>">
      </div>
      <div class="form-group">
        <label><img src="img/icons/26.png" alt="Senha" class="icon-xs"> Senha</label>
        <input type="password" name="senha" required placeholder="Sua senha">
      </div>
      <button type="submit" name="B1" class="btn-submit">Entrar</button>
    </form>
    <div class="auth-links">
      Não tem conta? <a href="cadastro-dados.php">Cadastre-se</a>
    </div>
    <div class="auth-links" style="margin-top:0.5rem">
      <a href="index.php">← Voltar à loja</a>
    </div>
  </div>
</body>
</html>