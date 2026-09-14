<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Cadastro - Labirinto Literário</title>
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
      <div class="step active">1</div>
      <div class="step-line"></div>
      <div class="step">2</div>
    </div>
    <h2>Dados Pessoais</h2>
    <form action="salvar-usuario.php" method="POST" id="form1">
      <div class="form-group">
        <label><img src="img/icons/22.png" alt="Nome" class="icon-xs"> Nome Completo *</label>
        <input type="text" name="nome" required placeholder="Seu nome completo">
      </div>
      <div class="form-group">
        <label><img src="img/icons/23.png" alt="CPF" class="icon-xs"> CPF *</label>
        <input type="text" name="cpf" required placeholder="000.000.000-00" maxlength="14" id="cpf">
      </div>
      <div class="form-group">
        <label><img src="img/icons/24.png" alt="Endereço" class="icon-xs"> Endereço *</label>
        <input type="text" name="endereco" required placeholder="Rua, número, complemento">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label><img src="img/icons/24.png" alt="Bairro" class="icon-xs"> Bairro *</label>
          <input type="text" name="bairro" required placeholder="Bairro">
        </div>
        <div class="form-group">
          <label><img src="img/icons/25.png" alt="CEP" class="icon-xs"> CEP *</label>
          <input type="text" name="cep" required placeholder="00000-000" maxlength="9" id="cep">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label><img src="img/icons/24.png" alt="Cidade" class="icon-xs"> Cidade *</label>
          <input type="text" name="cidade" required placeholder="Cidade">
        </div>
        <div class="form-group">
          <label><img src="img/icons/25.png" alt="Estado" class="icon-xs"> Estado *</label>
          <select name="estado" required>
            <option value="">UF</option>
            <?php
            $estados = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
            foreach ($estados as $uf):
            ?>
            <option value="<?=$uf?>"><?=$uf?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <button type="submit" name="B1" class="btn-submit">Próximo →</button>
    </form>
    <div class="auth-links">Já tem conta? <a href="login.php">Entrar</a></div>
  </div>
  <script>
    document.getElementById('cpf').addEventListener('input', function(e) {
      let v = e.target.value.replace(/\D/g,'');
      v = v.replace(/(\d{3})(\d)/,'$1.$2');
      v = v.replace(/(\d{3})(\d)/,'$1.$2');
      v = v.replace(/(\d{3})(\d{1,2})$/,'$1-$2');
      e.target.value = v;
    });
    document.getElementById('cep').addEventListener('input', function(e) {
      let v = e.target.value.replace(/\D/g,'');
      v = v.replace(/(\d{5})(\d)/,'$1-$2');
      e.target.value = v;
    });

  </script>
</body>
</html>