<?php
session_start();

// Limpa todas as variáveis da sessão
$_SESSION = [];

// Destroi a sessão no servidor
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroi a sessão e fecha o arquivo de sessão
session_destroy();
session_write_close();

// Redireciona para a página de login
header("Location: login.php");
exit;
?>
