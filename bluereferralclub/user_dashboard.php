<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. CÓDIGO PHP no topo preparando sessão, conexões e dados
session_start();
require_once 'conexao.php';

// Proteção: redireciona se não estiver logado
if (!isset($_SESSION['user_id'])) {
    header("Location: /../login.php");
    exit;
}

// Dados do usuário e suas estatísticas
$user_id = $_SESSION['user_id'];

// 1. Pega os dados principais do usuário, sem o commission_amount
$stmt = $conn->prepare("SELECT referral_code, first_name, referral_club_level_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($referral_code, $first_name, $referral_club_level_name);
$stmt->fetch();
$stmt->close();

// 2. Define a tabela de earnings dinamicamente com base no nome
$firstNameSanitized = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $first_name));
$referralCodeSanitized = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $referral_code));
$upcoming_view_name = $firstNameSanitized . $referralCodeSanitized . 'upcoming_payment_view';
$earnings_view_name = $firstNameSanitized . $referralCodeSanitized . 'total_earnings';

// 3. Busca os earnings da view apropriada, se existir
$commission_amount = 0.00;
$checkEarnings = $conn->prepare("SELECT TABLE_NAME FROM information_schema.VIEWS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?");
$checkEarnings->bind_param("s", $earnings_view_name);
$checkEarnings->execute();
$checkEarnings->store_result();
if ($checkEarnings->num_rows > 0) {
    $stmt = $conn->prepare("SELECT total_earnings FROM `$earnings_view_name` WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($commission_amount);
    $stmt->fetch();
    $stmt->close();
}
$checkEarnings->close();

// Busca posição no ranking
$ranking_result = $conn->query("SELECT id FROM users WHERE commission_amount IS NOT NULL ORDER BY commission_amount DESC");
$position = 0;
$rank = 1;
while ($row = $ranking_result->fetch_assoc()) {
    if ($row['id'] == $user_id) {
        $position = $rank;
        break;
    }
    $rank++;
}

// Top 3 do ranking geral
$top_query = $conn->query("
  SELECT first_name, commission_amount 
  FROM users 
  WHERE commission_amount IS NOT NULL 
  ORDER BY commission_amount DESC 
  LIMIT 3
");

$top_users = [];
while ($row = $top_query->fetch_assoc()) {
    $top_users[] = $row;
}

// Estatísticas de indicações
$stats = $conn->prepare("
  SELECT
    COUNT(*) AS total,
    SUM(status = 'Paid') AS paid,
    SUM(status = 'Successes') AS successes,
    SUM(status = 'Unsuccessful') AS unsuccessful,
    SUM(status = 'Pending') AS pending,
    SUM(status = 'Negotiating') AS negotiating
  FROM referrals
  WHERE referral_code = ?
");
$stats->bind_param("s", $referral_code);
$stats->execute();
$stats->bind_result($total, $paid, $successes, $unsuccessful, $pending, $negotiating);
$stats->fetch();
$stats->close();


// Referrals ainda não pagos
$query = $conn->prepare("
  SELECT 
    referred,
    status,
    commission_amount,
    city,
    DATE_FORMAT(created_at, '%d/%m/%Y') AS created_at
  FROM referrals 
  WHERE referral_code = ? AND (paid IS NULL OR paid = 0)
  ORDER BY created_at DESC
");
$query->bind_param("s", $referral_code);
$query->execute();
$res = $query->get_result();

$referrals = [];
while ($row = $res->fetch_assoc()) {
    $referrals[] = $row;
}
$query->close();

// Nome da view de upcoming payment
$upcoming_view_name = $firstNameSanitized . $referralCodeSanitized . 'upcoming_payment_view';

// Busca do total_earnings da view de upcoming payment, se existir
$upcoming_payment = 0.00;
$check = $conn->prepare("
    SELECT TABLE_NAME 
    FROM information_schema.VIEWS 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?
");
$check->bind_param("s", $upcoming_view_name);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $stmt = $conn->prepare("SELECT total_earnings FROM `$upcoming_view_name` WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $stmt->bind_result($upcoming_payment);
        $stmt->fetch();
    }
    $stmt->close();
}
$check->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Elegante</title>
<link rel="stylesheet" href="css/user_style.css">
</head>
<body>

  <!-- HEADER substituindo a div .top -->
  <header class="main-header">
    <div class="header-banner">
      <img src="https://bluefacilityservices.com.au/bluereferralclub/image/header_referral.webp" alt="Header Banner" class="banner-image" />
    </div>

    <nav class="header-nav">
        <div class="logo-area">
            <span class="logo-link">Hello, <strong><?= htmlspecialchars($first_name) ?>!</strong></span>
        </div>

      <div class="header-actions">
        <button class="btn-referral-share" id="btnShareReferral">Share your Referral Code</button>
        <input type="hidden" id="referral_code" value="<?= htmlspecialchars($referral_code) ?>">

        <button class="btn-referral-give menu-item" data-modal="referral/index_user.php" id="btnGiveReferral">Give a Referral</button>

        <div class="account-menu" id="accountMenu">
          <button class="account-icon-text" id="accountToggle" aria-label="Account Menu">Account</button>
          <div class="account-dropdown" id="accountDropdown">
            <ul class="account-dropdown-menu">
              <li><a href="#" data-modal="header_component/profile.php" class="menu-item">Profile</a></li>
              <li><a href="#" data-modal="header_component/password.php" class="menu-item">Password</a></li>
              <li><a href="#" data-modal="header_component/bank.php" class="menu-item">Bank Details</a></li>
              <li><a href="/payment-history" class="menu-item">Payment History</a></li>
              <li><a href="referral_history.php">Referrals</a></li>
            </ul>
          </div>
        </div>

        <button class="btn-logout" id="btnLogout">Logout</button>
      </div>
    </nav>
  </header>

<!-- ÁREA DO MEIO -->
<div class="middle">
  <div>
    Your referral code is <strong><?= htmlspecialchars($referral_code) ?></strong><br>
<?php if ($successes > 0): ?>
  Category in the club is <strong><?= htmlspecialchars($referral_club_level_name) ?></strong><br>
<?php endif; ?>
Your position in the referral ranking is <strong>Top <?= $position ?></strong><br><br>

    <strong>Ranking:</strong><br>
    <?php if (count($top_users) > 0): ?>
      <?php foreach ($top_users as $i => $top): ?>
        Top <?= $i + 1 ?> (<?= htmlspecialchars($top['first_name']) ?> — $<?= number_format($top['commission_amount'], 2, ',', '.') ?>)
        <?= $i < count($top_users) - 1 ? ' | ' : '' ?>
      <?php endforeach; ?>
    <?php else: ?>
      No ranking data available.
    <?php endif; ?>
  </div>

  <div>
    <p>Total earnings: $ <strong><?= number_format($commission_amount, 2, ',', '.') ?></strong></p>
    <p>Upcoming payment: $ <strong><?= number_format($upcoming_payment, 2, ',', '.') ?></strong></p>

<p>
  Total: <strong><?= (int)$total ?></strong> |
  Paid: <strong><?= (int)$paid ?></strong> |
  Successes: <strong><?= (int)$successes ?></strong> |
  Unsuccessful: <strong><?= (int)$unsuccessful ?></strong> |
  Pending: <strong><?= (int)$pending ?></strong> |
  Negotiating: <strong><?= (int)$negotiating ?></strong>
</p>

  </div>
</div>

<!-- ÁREA INFERIOR -->
<div class="bottom">
  <h3>Referrals Overview</h3>
  <table border="1" style="width: 100%; border-collapse: collapse; color: white;">
    <thead>
      <tr>
        <th>Referred</th>
        <th>Status</th>
        <th>Commission Amount</th>
        <th>City</th>
        <th>Created At</th>
      </tr>
    </thead>
    <tbody>
<?php foreach ($referrals as $row): ?>
  <?php 
    $status = strtolower($row['status']);
    if ($status === 'paid' || $status === 'unsuccessful') continue;
  ?>
  <tr>
    <td><input type="text" value="<?= htmlspecialchars($row['referred'] ?? '') ?>" readonly></td>
    <td><input type="text" value="<?= htmlspecialchars($row['status'] ?? '') ?>" readonly></td>
    <td><input type="text" value="<?= htmlspecialchars($row['commission_amount'] ?? '') ?>" readonly></td>
    <td><input type="text" value="<?= htmlspecialchars($row['city'] ?? '') ?>" readonly></td>
    <td><input type="text" value="<?= htmlspecialchars($row['created_at'] ?? '') ?>" readonly></td>
  </tr>
<?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- MODAL -->
<div id="formModal" class="modal">
  <div class="modal-content">
    <span class="close-button">&times;</span>
    <div id="modalBody"></div>
  </div>
</div>
  
<!-- Modal para compartilhar -->
<div id="shareModal" class="modal" style="display:none;">
  <div class="modal-content">
    <button class="close-modal" id="closeShareModal" aria-label="Close modal">&times;</button>
    <h3>Share your referral link!</h3>
    <input type="text" id="referralLink" readonly style="width:100%; margin-bottom: 10px;">
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
      <a id="whatsappShare" class="btn btn-submit" target="_blank">WhatsApp</a>
      <a id="facebookShare" class="btn btn-submit" target="_blank">Facebook</a>
      <a id="linkedinShare" class="btn btn-submit" target="_blank">LinkedIn</a>
      <button id="copyLink" class="btn btn-orange">Copy Link</button>
    </div>
  </div>
</div>

<!-- SCRIPT para interações com modal -->
<script src="js/user_script.js"></script>

</body>
</html>
