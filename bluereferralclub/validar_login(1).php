<?php
require_once 'conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        die("⚠️ Preencha todos os campos.");
    }

    // Buscar o usuário pelo email
    $stmt = $conn->prepare("SELECT id, user_type, password FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user && password_verify($password, $user['password'])) {
        // Login ok
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];

        // ✅ Se for admin, atualiza os dados de referrals na tabela users
        if ($user['user_type'] === 'admin') {
            require_once 'sync_referrals.php'; // <- já chama corretamente
            header("Location: referrals_admin.php");
        } else {
            header("Location: painel_usuario.php");
        }

        exit;
    } else {
        echo "❌ Usuário ou senha inválidos.";
    }
}
?>
