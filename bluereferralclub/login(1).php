<?php
require_once 'conexao.php';
?>

<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Login - Projeto</title>
  <link rel="stylesheet" href="css/login-style.css">
  <style>
    .input-icon-wrapper {
      position: relative;
    }
    .input-icon-wrapper input[type="password"],
    .input-icon-wrapper input[type="text"] {
      padding-right: 40px;
    }
    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }
    .toggle-password img {
      width: 20px;
      opacity: 0.6;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-box">
      <h2>Blue Referral Club</h2>

      <!-- Login com EMAIL -->
      <form action="validar_login.php" method="POST" id="loginForm">
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
          <label for="password">Senha</label>
          <div class="input-icon-wrapper">
            <input type="password" name="password" id="password" placeholder="Senha" required>
            <span class="toggle-password" onclick="togglePassword(this, 'password')">
              <img src="assets/img/eye.svg" alt="Mostrar senha" id="toggleIcon">
            </span>
          </div>
        </div>

        <button type="submit" class="btn-gold">Entrar</button>
      </form>

      <!-- Link "Esqueci a senha" -->
      <p style="margin-top: 15px;">
        <a href="#" id="forgotPasswordLink" style="color: white;">Esqueceu a senha?</a>
      </p>

      <!-- Formulário para redefinir senha -->
      <form id="forgotPasswordForm" style="display: none; margin-top: 20px;">
        <div class="form-group">
          <label for="resetEmail">Digite seu e-mail para redefinir</label>
          <input type="email" id="resetEmail" name="resetEmail" placeholder="Seu e-mail" required>
        </div>
        <button type="submit" class="btn-gold">Enviar redefinição</button>
      </form>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    document.getElementById("forgotPasswordLink").addEventListener("click", function (e) {
      e.preventDefault();
      document.getElementById("forgotPasswordForm").style.display = "block";
      document.getElementById("loginForm").style.display = "none";
    });

    document.getElementById("forgotPasswordForm").addEventListener("submit", async function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const response = await fetch("send_reset_email.php", {
        method: "POST",
        body: formData
      });
      const data = await response.json();
      if (data.success) {
        alert(data.success);
        this.reset();
      } else {
        alert(data.error || "Erro ao processar solicitação.");
      }
    });

    function togglePassword(el, inputId) {
      const input = document.getElementById(inputId);
      const icon = el.querySelector("img");
      if (input.type === "password") {
        input.type = "text";
        icon.src = "assets/img/eye-off.svg";
      } else {
        input.type = "password";
        icon.src = "assets/img/eye.svg";
      }
    }
  </script>
</body>
</html>
