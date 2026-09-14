<?php
session_start();
if (!isset($_SESSION['cadastro_dados'])) { header('Location: cadastro-dados.php'); exit; }
$erro = '';
if (isset($_SESSION['login_erro'])) {
    $erro = $_SESSION['login_erro'];
    unset($_SESSION['login_erro']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Acesso - Labirinto Literário</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/geral.css">
    <link rel="stylesheet" href="css/autenticacao.css">
</head>
<body class="auth-page">
  <div class="auth-box">
    <div class="auth-logo">
      <a href="index.php" style="text-decoration:none"><img src="img/icons/1.png" alt="Labirinto Literário" class="auth-logo-img"></a>
    </div>
    <div class="step-indicator">
      <div class="step done">✓</div>
      <div class="step-line"></div>
      <div class="step active">2</div>
    </div>
    <h2>Criar Acesso</h2>
    <?php if ($erro): ?>
      <div class="alert alert-error"><?php echo htmlspecialchars($erro) ?></div>
    <?php endif; ?>
    <p style="color:#5a9abf;font-size:0.9rem;margin-bottom:1.25rem">
      Olá, <strong><?php echo htmlspecialchars($_SESSION['cadastro_dados']['nome']) ?></strong>! Defina seu login e senha.
    </p>
    <form action="salvar-acesso.php" method="POST">
      <div class="form-group">
        <label><img src="img/icons/22.png" alt="Login" class="icon-xs"> Login *</label>
        <input type="text" name="login" required placeholder="Escolha um nome de usuário" autocomplete="username">
      </div>
      <div class="form-group">
        <label><img src="img/icons/26.png" alt="Senha" class="icon-xs"> Senha *</label>
        <input type="password" name="senha" required placeholder="Mínimo 6 caracteres" minlength="6" id="s1">
      </div>
      <div class="form-group">
        <label><img src="img/icons/26.png" alt="Confirmar Senha" class="icon-xs"> Confirmar Senha *</label>
        <input type="password" name="senha2" required placeholder="Repita a senha" id="s2">
      </div>
      <button type="submit" name="B1" class="btn-submit">Finalizar Cadastro</button>
    </form>
    <div class="auth-links"><a href="cadastro-dados.php">← Voltar</a></div>
  </div>
  <script>
    document.querySelector('form').addEventListener('submit', function(e) {
      if (document.getElementById('s1').value !== document.getElementById('s2').value) {
        alert('As senhas não coincidem.'); e.preventDefault();
      }
    });
  </script>
</body>
</html>